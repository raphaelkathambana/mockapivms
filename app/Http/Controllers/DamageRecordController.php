<?php

namespace App\Http\Controllers;

use App\Models\DamageRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DamageRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $damageRecords = DamageRecord::with('vehicle')->get();
        return response()->json($damageRecords);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'damage_date' => 'required|date',
            'damage_description' => 'required|string',
            'repair_status' => 'required|string|in:Pending,In Progress,Completed,Not Repairable',
            'repair_cost' => 'nullable|numeric|min:0',
            'repair_date' => 'nullable|date|after_or_equal:damage_date',
            'insurance_claim_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $damageRecord = DamageRecord::create($request->all());

            DB::commit();

            return response()->json($damageRecord, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create damage record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $damageRecord = DamageRecord::with('vehicle')->findOrFail($id);
        return response()->json($damageRecord);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $damageRecord = DamageRecord::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'damage_date' => 'sometimes|required|date',
            'damage_description' => 'sometimes|required|string',
            'repair_status' => 'sometimes|required|string|in:Pending,In Progress,Completed,Not Repairable',
            'repair_cost' => 'nullable|numeric|min:0',
            'repair_date' => 'nullable|date|after_or_equal:damage_date',
            'insurance_claim_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $damageRecord->update($request->all());

            DB::commit();

            return response()->json($damageRecord);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update damage record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $damageRecord = DamageRecord::findOrFail($id);

        try {
            DB::beginTransaction();

            $damageRecord->delete();

            DB::commit();

            return response()->json(['message' => 'Damage record deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete damage record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get damage records by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $damageRecords = DamageRecord::where('vin', $vin)->get();
        return response()->json($damageRecords);
    }

    /**
     * Get vehicles for damage record form
     */
    public function getVehicles()
    {
        $vehicles = Vehicle::all();
        return response()->json($vehicles);
    }
}
