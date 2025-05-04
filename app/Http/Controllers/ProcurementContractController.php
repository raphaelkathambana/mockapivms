<?php

namespace App\Http\Controllers;

use App\Models\ProcurementContract;
use App\Models\Vehicle;
use App\Models\Seller;
use App\Models\Employee;
use App\Models\PurchaseLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProcurementContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procurementContracts = ProcurementContract::with(['vehicle', 'seller', 'employee'])->get();
        return response()->json($procurementContracts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'contract_date' => 'required|date',
            'signature' => 'required|string',
            'contract_price' => 'required|numeric|min:0',
            'seller_id' => 'nullable|exists:sellers,seller_id',
            'employee_id' => 'nullable|exists:employees,employee_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Create the procurement contract
            $procurementContract = ProcurementContract::create([
                'vin' => $request->vin,
                'seller_id' => $request->seller_id,
                'employee_id' => $request->employee_id,
                'contract_date' => $request->contract_date,
                'digital_signature' => $request->signature, // Store signature blob as digital_signature
                'signature' => $request->signature, // Also store in signature field for backward compatibility
                'purchase_amount' => $request->contract_price,
            ]);

            // Update the vehicle with procurement contract information
            $vehicle = Vehicle::findOrFail($request->vin);
            $vehicle->update([
                'status' => 'Processing', // Set to processing until purchase log is created
                'purchase_price' => $request->contract_price,
            ]);

            DB::commit();

            return response()->json($procurementContract, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create procurement contract: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $procurementContract = ProcurementContract::with(['vehicle', 'seller', 'employee'])->findOrFail($id);
        return response()->json($procurementContract);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $procurementContract = ProcurementContract::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'seller_id' => 'sometimes|required|exists:sellers,seller_id',
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'contract_date' => 'sometimes|required|date',
            'digital_signature' => 'nullable',
            'purchase_amount' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If VIN is changing, update old and new vehicle information
            if (isset($request->vin) && $request->vin !== $procurementContract->vin) {
                // Reset old vehicle if not sold
                $oldVehicle = Vehicle::findOrFail($procurementContract->vin);
                if ($oldVehicle->status !== 'Sold') {
                    $oldVehicle->update([
                        'seller_id' => null,
                        'status' => null, // or whatever default status you want
                    ]);
                }

                // Update new vehicle
                $newVehicle = Vehicle::findOrFail($request->vin);
                $newVehicle->update([
                    'seller_id' => $request->seller_id ?? $procurementContract->seller_id,
                    'status' => 'Available', // or whatever initial status
                    'purchase_price' => $request->purchase_amount ?? $procurementContract->purchase_amount,
                ]);

                // Find and update related purchase log
                $relatedLog = PurchaseLog::where('vin', $procurementContract->vin)
                    ->where('seller_id', $procurementContract->seller_id)
                    ->where('status_change', 'Vehicle Purchased via Contract')
                    ->first();

                if ($relatedLog) {
                    $relatedLog->update([
                        'vin' => $request->vin,
                        'seller_id' => $request->seller_id ?? $procurementContract->seller_id,
                        'employee_id' => $request->employee_id ?? $procurementContract->employee_id,
                        'timestamp' => $request->contract_date ?? $procurementContract->contract_date,
                    ]);
                }
            }
            // If only seller is changing
            elseif (isset($request->seller_id) && $request->seller_id !== $procurementContract->seller_id) {
                $vehicle = Vehicle::findOrFail($procurementContract->vin);
                if ($vehicle->status !== 'Sold') {
                    $vehicle->update([
                        'seller_id' => $request->seller_id,
                    ]);
                }

                // Find and update related purchase log
                $relatedLog = PurchaseLog::where('vin', $procurementContract->vin)
                    ->where('seller_id', $procurementContract->seller_id)
                    ->where('status_change', 'Vehicle Purchased via Contract')
                    ->first();

                if ($relatedLog) {
                    $relatedLog->update([
                        'seller_id' => $request->seller_id,
                    ]);
                }
            }
            // If purchase amount is changing
            elseif (isset($request->purchase_amount) && $request->purchase_amount !== $procurementContract->purchase_amount) {
                $vehicle = Vehicle::findOrFail($procurementContract->vin);
                $vehicle->update([
                    'purchase_price' => $request->purchase_amount,
                ]);
            }

            $procurementContract->update($request->all());

            DB::commit();

            return response()->json($procurementContract);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update procurement contract: '.$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $procurementContract = ProcurementContract::findOrFail($id);

        try {
            DB::beginTransaction();

            // If the vehicle is not sold, reset its seller information
            $vehicle = Vehicle::findOrFail($procurementContract->vin);
            if ($vehicle->status !== 'Sold') {
                $vehicle->update([
                    'seller_id' => null,
                    'status' => null, // or whatever default status
                    'purchase_price' => 0, // or null, depending on your schema
                ]);
            }

            // Delete related purchase log
            PurchaseLog::where('vin', $procurementContract->vin)
                ->where('seller_id', $procurementContract->seller_id)
                ->where('status_change', 'Vehicle Purchased via Contract')
                ->delete();

            $procurementContract->delete();

            DB::commit();

            return response()->json(['message' => 'Procurement contract deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete procurement contract: '.$e->getMessage()], 500);
        }
    }

    /**
     * Get related data for procurement contract form
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
     * Get procurement contracts by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $procurementContracts = ProcurementContract::with(['seller', 'employee'])
            ->where('vin', $vin)
            ->orderBy('contract_date', 'desc')
            ->get();

        return response()->json($procurementContracts);
    }

    /**
     * Get procurement contracts by seller
     */
    public function getBySeller(int $sellerId)
    {
        $procurementContracts = ProcurementContract::with(['vehicle', 'employee'])
            ->where('seller_id', $sellerId)
            ->orderBy('contract_date', 'desc')
            ->get();

        return response()->json($procurementContracts);
    }

    /**
     * Get procurement contracts by employee
     */
    public function getByEmployee(int $employeeId)
    {
        $procurementContracts = ProcurementContract::with(['vehicle', 'seller'])
            ->where('employee_id', $employeeId)
            ->orderBy('contract_date', 'desc')
            ->get();

        return response()->json($procurementContracts);
    }
}
