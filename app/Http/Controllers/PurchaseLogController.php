<?php

namespace App\Http\Controllers;

use App\Models\PurchaseLog;
use App\Models\Vehicle;
use App\Models\Seller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseLogs = PurchaseLog::with(['vehicle', 'seller', 'employee'])->get();
        return response()->json($purchaseLogs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'seller_id' => 'required|exists:sellers,seller_id',
            'employee_id' => 'required|exists:employees,employee_id',
            'timestamp' => 'required|date',
            'status_change' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Create the purchase log
            $purchaseLog = PurchaseLog::create([
                'timestamp' => $request->timestamp,
                'vin' => $request->vin,
                'employee_id' => $request->employee_id,
                'seller_id' => $request->seller_id,
                'status_change' => $request->status_change,
            ]);

            // If the status change is "Vehicle Purchased" or similar final status,
            // update the vehicle's seller_id and status
            if (in_array($request->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory'])) {
                $vehicle = Vehicle::findOrFail($request->vin);
                $vehicle->update([
                    'seller_id' => $request->seller_id,
                    'status' => 'Available', // or whatever your initial inventory status is
                ]);
            }

            DB::commit();

            return response()->json($purchaseLog, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create purchase log: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $purchaseLog = PurchaseLog::with(['vehicle', 'seller', 'employee'])->findOrFail($id);
        return response()->json($purchaseLog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $purchaseLog = PurchaseLog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'seller_id' => 'sometimes|required|exists:sellers,seller_id',
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'timestamp' => 'sometimes|required|date',
            'status_change' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If VIN is changing, handle vehicle relationship updates
            if (isset($request->vin) && $request->vin !== $purchaseLog->vin) {
                // If the old purchase log had a final status, reset the old vehicle
                if (in_array($purchaseLog->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory'])) {
                    $oldVehicle = Vehicle::findOrFail($purchaseLog->vin);
                    $oldVehicle->update([
                        'seller_id' => null,
                    ]);
                }

                // If the new status is final, update the new vehicle
                if (in_array($request->status_change ?? $purchaseLog->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory'])) {
                    $newVehicle = Vehicle::findOrFail($request->vin);
                    $newVehicle->update([
                        'seller_id' => $request->seller_id ?? $purchaseLog->seller_id,
                        'status' => 'Available', // or whatever your initial inventory status is
                    ]);
                }
            }
            // If only seller is changing on a final status log
            elseif (isset($request->seller_id) &&
                   $request->seller_id !== $purchaseLog->seller_id &&
                   in_array($purchaseLog->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory'])) {
                $vehicle = Vehicle::findOrFail($purchaseLog->vin);
                $vehicle->update([
                    'seller_id' => $request->seller_id,
                ]);
            }
            // If status is changing to a final one
            elseif (isset($request->status_change) &&
                   !in_array($purchaseLog->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory']) &&
                   in_array($request->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory'])) {
                $vehicle = Vehicle::findOrFail($purchaseLog->vin);
                $vehicle->update([
                    'seller_id' => $purchaseLog->seller_id,
                    'status' => 'Available', // or whatever your initial inventory status is
                ]);
            }

            $purchaseLog->update($request->all());

            DB::commit();

            return response()->json($purchaseLog);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update purchase log: '.$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $purchaseLog = PurchaseLog::findOrFail($id);

        try {
            DB::beginTransaction();

            // If this was a final status log, consider resetting the vehicle
            if (in_array($purchaseLog->status_change, ['Vehicle Purchased', 'Vehicle Added to Inventory'])) {
                $vehicle = Vehicle::findOrFail($purchaseLog->vin);

                // Check if there are other purchase logs for this vehicle
                $otherLogs = PurchaseLog::where('vin', $purchaseLog->vin)
                    ->where('log_id', '!=', $id)
                    ->where('status_change', 'Vehicle Purchased')
                    ->orWhere('status_change', 'Vehicle Added to Inventory')
                    ->exists();

                if (!$otherLogs) {
                    $vehicle->update([
                        'seller_id' => null,
                        // You might not want to change status if the vehicle is already sold
                        // So only reset if it's still in inventory
                        'status' => $vehicle->status !== 'Sold' ? null : $vehicle->status,
                    ]);
                }
            }

            $purchaseLog->delete();

            DB::commit();

            return response()->json(['message' => 'Purchase log deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete purchase log: '.$e->getMessage()], 500);
        }
    }

    /**
     * Get related data for purchase log form
     */
    public function getRelatedData()
    {
        $data = [
            'vehicles' => Vehicle::all(),
            'sellers' => Seller::all(),
            'employees' => Employee::all(),
        ];

        return response()->json($data);
    }

    /**
     * Get purchase logs by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $purchaseLogs = PurchaseLog::with(['seller', 'employee'])
            ->where('vin', $vin)
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json($purchaseLogs);
    }

    /**
     * Get purchase logs by seller
     */
    public function getBySeller(int $sellerId)
    {
        $purchaseLogs = PurchaseLog::with(['vehicle', 'employee'])
            ->where('seller_id', $sellerId)
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json($purchaseLogs);
    }

    /**
     * Get purchase logs by employee
     */
    public function getByEmployee(int $employeeId)
    {
        $purchaseLogs = PurchaseLog::with(['vehicle', 'seller'])
            ->where('employee_id', $employeeId)
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json($purchaseLogs);
    }
}
