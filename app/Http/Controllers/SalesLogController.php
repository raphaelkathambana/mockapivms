<?php

namespace App\Http\Controllers;

use App\Models\SalesLog;
use App\Models\Vehicle;
use App\Models\Buyer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SalesLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesLogs = SalesLog::with(['vehicle', 'buyer', 'employee'])->get();
        return response()->json($salesLogs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'customer_id' => 'required|exists:buyers,customer_id',
            'employee_id' => 'required|exists:employees,employee_id',
            'sale_date' => 'required|date',
            'sale_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,Credit Card,Bank Transfer,Financing,Leasing',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Check if vehicle is available or reserved
            $vehicle = Vehicle::findOrFail($request->vin);
            if ($vehicle->status === 'Sold') {
                return response()->json(['error' => 'Vehicle is already sold'], 422);
            }

            // Create the sales log
            $salesLog = SalesLog::create($request->all());

            // Update vehicle status to Sold and set buyer_id
            $vehicle->update([
                'status' => 'Sold',
                'buyer_id' => $request->customer_id,
                'customer_number' => Buyer::findOrFail($request->customer_id)->customer_number
            ]);

            DB::commit();

            return response()->json($salesLog, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create sales log: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $salesLog = SalesLog::with(['vehicle', 'buyer', 'employee'])->findOrFail($id);
        return response()->json($salesLog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $salesLog = SalesLog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'customer_id' => 'sometimes|required|exists:buyers,customer_id',
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'sale_date' => 'sometimes|required|date',
            'sale_price' => 'sometimes|required|numeric|min:0',
            'payment_method' => 'sometimes|required|string|in:Cash,Credit Card,Bank Transfer,Financing,Leasing',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If VIN is changing, update old and new vehicle statuses
            if (isset($request->vin) && $request->vin !== $salesLog->vin) {
                // Reset old vehicle
                $oldVehicle = Vehicle::findOrFail($salesLog->vin);
                $oldVehicle->update([
                    'status' => 'Available',
                    'buyer_id' => null,
                    'customer_number' => null
                ]);

                // Check if new vehicle is available
                $newVehicle = Vehicle::findOrFail($request->vin);
                if ($newVehicle->status === 'Sold') {
                    return response()->json(['error' => 'New vehicle is already sold'], 422);
                }

                // Update new vehicle
                $newVehicle->update([
                    'status' => 'Sold',
                    'buyer_id' => $request->customer_id ?? $salesLog->customer_id,
                    'customer_number' => isset($request->customer_id)
                        ? Buyer::findOrFail($request->customer_id)->customer_number
                        : Buyer::findOrFail($salesLog->customer_id)->customer_number
                ]);
            }
            // If only customer is changing
            elseif (isset($request->customer_id) && $request->customer_id !== $salesLog->customer_id) {
                $vehicle = Vehicle::findOrFail($salesLog->vin);
                $vehicle->update([
                    'buyer_id' => $request->customer_id,
                    'customer_number' => Buyer::findOrFail($request->customer_id)->customer_number
                ]);
            }

            $salesLog->update($request->all());

            DB::commit();

            return response()->json($salesLog);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update sales log: '.$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $salesLog = SalesLog::findOrFail($id);

        try {
            DB::beginTransaction();

            // Reset vehicle status
            $vehicle = Vehicle::findOrFail($salesLog->vin);
            $vehicle->update([
                'status' => 'Available',
                'buyer_id' => null,
                'customer_number' => null
            ]);

            $salesLog->delete();

            DB::commit();

            return response()->json(['message' => 'Sales log deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete sales log: '.$e->getMessage()], 500);
        }
    }

    /**
     * Get related data for sales log form
     */
    public function getRelatedData()
    {
        $data = [
            'vehicles' => Vehicle::where('status', 'Available')->orWhere('status', 'Reserved')->with(['manufacturer', 'model'])->get(),
            'buyers' => Buyer::all(),
            'employees' => Employee::all(),
        ];

        return response()->json($data);
    }

    /**
     * Get sales logs by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $salesLogs = SalesLog::with(['buyer', 'employee'])
            ->where('vin', $vin)
            ->orderBy('sale_date', 'desc')
            ->get();

        return response()->json($salesLogs);
    }

    /**
     * Get sales logs by buyer
     */
    public function getByBuyer(int $buyerId)
    {
        $salesLogs = SalesLog::with(['vehicle', 'employee'])
            ->where('customer_id', $buyerId)
            ->orderBy('sale_date', 'desc')
            ->get();

        return response()->json($salesLogs);
    }

    /**
     * Get sales logs by employee
     */
    public function getByEmployee(int $employeeId)
    {
        $salesLogs = SalesLog::with(['vehicle', 'buyer'])
            ->where('employee_id', $employeeId)
            ->orderBy('sale_date', 'desc')
            ->get();

        return response()->json($salesLogs);
    }
}
