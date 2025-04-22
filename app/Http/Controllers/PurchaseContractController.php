<?php

namespace App\Http\Controllers;

use App\Models\PurchaseContract;
use App\Models\Vehicle;
use App\Models\Buyer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = PurchaseContract::with(['vehicle', 'buyer', 'employee'])->get();
        return response()->json($contracts);
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
            'contract_date' => 'required|date',
            'digital_signature' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Check if vehicle is available
            $vehicle = Vehicle::findOrFail($request->vin);
            if ($vehicle->status !== 'Available') {
                return response()->json(['error' => 'Vehicle is not available for purchase'], 422);
            }

            // Create the purchase contract
            $contract = PurchaseContract::create($request->all());

            // Update vehicle status to Sold and set buyer_id
            $vehicle->update([
                'status' => 'Sold',
                'buyer_id' => $request->customer_id,
                'customer_number' => Buyer::findOrFail($request->customer_id)->customer_number
            ]);

            DB::commit();

            return response()->json($contract, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create purchase contract: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $contract = PurchaseContract::with(['vehicle', 'buyer', 'employee'])->findOrFail($id);
        return response()->json($contract);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $contract = PurchaseContract::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'customer_id' => 'sometimes|required|exists:buyers,customer_id',
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'contract_date' => 'sometimes|required|date',
            'digital_signature' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If VIN is changing, update old and new vehicle statuses
            if (isset($request->vin) && $request->vin !== $contract->vin) {
                // Reset old vehicle
                $oldVehicle = Vehicle::findOrFail($contract->vin);
                $oldVehicle->update([
                    'status' => 'Available',
                    'buyer_id' => null,
                    'customer_number' => null
                ]);

                // Check if new vehicle is available
                $newVehicle = Vehicle::findOrFail($request->vin);
                if ($newVehicle->status !== 'Available') {
                    return response()->json(['error' => 'New vehicle is not available for purchase'], 422);
                }

                // Update new vehicle
                $newVehicle->update([
                    'status' => 'Sold',
                    'buyer_id' => $request->customer_id ?? $contract->customer_id,
                    'customer_number' => isset($request->customer_id)
                        ? Buyer::findOrFail($request->customer_id)->customer_number
                        : Buyer::findOrFail($contract->customer_id)->customer_number
                ]);
            }
            // If only customer is changing
            elseif (isset($request->customer_id) && $request->customer_id !== $contract->customer_id) {
                $vehicle = Vehicle::findOrFail($contract->vin);
                $vehicle->update([
                    'buyer_id' => $request->customer_id,
                    'customer_number' => Buyer::findOrFail($request->customer_id)->customer_number
                ]);
            }

            $contract->update($request->all());

            DB::commit();

            return response()->json($contract);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update purchase contract: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $contract = PurchaseContract::findOrFail($id);

        try {
            DB::beginTransaction();

            // Reset vehicle status
            $vehicle = Vehicle::findOrFail($contract->vin);
            $vehicle->update([
                'status' => 'Available',
                'buyer_id' => null,
                'customer_number' => null
            ]);

            $contract->delete();

            DB::commit();

            return response()->json(['message' => 'Purchase contract deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete purchase contract: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get related data for purchase contract form
     */
    public function getRelatedData()
    {
        $data = [
            'vehicles' => Vehicle::where('status', 'Available')->with(['manufacturer', 'model'])->get(),
            'buyers' => Buyer::all(),
            'employees' => Employee::all(),
        ];

        return response()->json($data);
    }
}
