<?php

namespace App\Http\Controllers;

use App\Models\InspectionRecord;
use App\Models\Vehicle;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InspectionRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspectionRecords = InspectionRecord::with(['vehicle', 'employee'])->get();
        return response()->json($inspectionRecords);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'inspection_date' => 'required|date',
            'inspection_type' => 'required|string|in:Pre-Purchase,General,Safety,Emissions,Warranty',
            'employee_id' => 'required|exists:employees,employee_id',
            'mileage_at_inspection' => 'required|integer|min:0',
            'passed' => 'required|boolean',
            'notes' => 'nullable|string',
            'next_inspection_date' => 'nullable|date|after:inspection_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $inspectionRecord = InspectionRecord::create($request->all());

            // If this is a general inspection, update the vehicle's next due date
            if ($request->inspection_type === 'General' && $request->next_inspection_date) {
                $vehicle = Vehicle::findOrFail($request->vin);
                $vehicle->update([
                    'general_inspection_next_due_date' => $request->next_inspection_date
                ]);
            }

            DB::commit();

            return response()->json($inspectionRecord, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create inspection record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $inspectionRecord = InspectionRecord::with(['vehicle', 'employee'])->findOrFail($id);
        return response()->json($inspectionRecord);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $inspectionRecord = InspectionRecord::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'inspection_date' => 'sometimes|required|date',
            'inspection_type' => 'sometimes|required|string|in:Pre-Purchase,General,Safety,Emissions,Warranty',
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'mileage_at_inspection' => 'sometimes|required|integer|min:0',
            'passed' => 'sometimes|required|boolean',
            'notes' => 'nullable|string',
            'next_inspection_date' => 'nullable|date|after:inspection_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $inspectionRecord->update($request->all());

            // If this is a general inspection and next_inspection_date is updated, update the vehicle's next due date
            if (
                ($request->inspection_type === 'General' || $inspectionRecord->inspection_type === 'General') &&
                isset($request->next_inspection_date)
            ) {
                $vehicle = Vehicle::findOrFail($request->vin ?? $inspectionRecord->vin);
                $vehicle->update([
                    'general_inspection_next_due_date' => $request->next_inspection_date
                ]);
            }

            DB::commit();

            return response()->json($inspectionRecord);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update inspection record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $inspectionRecord = InspectionRecord::findOrFail($id);

        try {
            DB::beginTransaction();

            $inspectionRecord->delete();

            DB::commit();

            return response()->json(['message' => 'Inspection record deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete inspection record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get inspection records by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $inspectionRecords = InspectionRecord::with('employee')
            ->where('vin', $vin)
            ->orderBy('inspection_date', 'desc')
            ->get();

        return response()->json($inspectionRecords);
    }

    /**
     * Get related data for inspection record form
     */
    public function getRelatedData()
    {
        $data = [
            'vehicles' => Vehicle::all(),
            'employees' => Employee::all(),
        ];

        return response()->json($data);
    }
}
