<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Manufacturer;
use App\Models\VehicleModel;
use App\Models\Transmission;
use App\Models\VehicleType;
use App\Models\DriveType;
use App\Models\OwnerType;
use App\Models\Buyer;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::with([
            'manufacturer',
            'model',
            'transmission',
            'vehicleType',
            'driveType',
            'ownerType',
            'buyer',
            'seller'
        ])->get();

        return response()->json($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|max:17|unique:vehicles,vin',
            'manufacturer_id' => 'required|exists:manufacturers,manufacturer_id',
            'model_id' => 'required|exists:models,model_id',
            'first_registration' => 'nullable|date',
            'mileage' => 'required|integer|min:0',
            'transmission_id' => 'required|exists:transmissions,transmission_id',
            'type_id' => 'required|exists:vehicle_types,type_id',
            'drive_type_id' => 'required|exists:drive_types,drive_type_id',
            'color' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'num_previous_owners' => 'required|integer|min:0',
            'owner_type_id' => 'required|exists:owner_types,owner_type_id',
            'general_inspection_next_due_date' => 'nullable|date',
            'evaluation_date' => 'nullable|date',
            'days_on_stock' => 'nullable|integer|min:0',
            'buyer_id' => 'nullable|exists:buyers,customer_id',
            'seller_id' => 'nullable|exists:sellers,seller_id',
            'status' => 'required|string|in:Available,Sold,Reserved',
            'customer_number' => 'nullable|exists:buyers,customer_number',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Set last_edited_date to current date
        $data = $request->all();
        $data['last_edited_date'] = now();

        try {
            DB::beginTransaction();

            $vehicle = Vehicle::create($data);

            DB::commit();

            return response()->json($vehicle, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create vehicle: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $vin)
    {
        $vehicle = Vehicle::with([
            'manufacturer',
            'model',
            'transmission',
            'vehicleType',
            'driveType',
            'ownerType',
            'buyer',
            'seller',
            'engineSpecification',
            'vehicleRegistration',
            'damageRecords',
            'tires',
            'inspectionRecords',
            'additionalEquipment',
            'salesLogs'
        ])->findOrFail($vin);

        return response()->json($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $vin)
    {
        $vehicle = Vehicle::findOrFail($vin);

        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'sometimes|required|exists:manufacturers,manufacturer_id',
            'model_id' => 'sometimes|required|exists:models,model_id',
            'first_registration' => 'nullable|date',
            'mileage' => 'sometimes|required|integer|min:0',
            'transmission_id' => 'sometimes|required|exists:transmissions,transmission_id',
            'type_id' => 'sometimes|required|exists:vehicle_types,type_id',
            'drive_type_id' => 'sometimes|required|exists:drive_types,drive_type_id',
            'color' => 'sometimes|required|string|max:50',
            'purchase_price' => 'sometimes|required|numeric|min:0',
            'selling_price' => 'sometimes|required|numeric|min:0',
            'num_previous_owners' => 'sometimes|required|integer|min:0',
            'owner_type_id' => 'sometimes|required|exists:owner_types,owner_type_id',
            'general_inspection_next_due_date' => 'nullable|date',
            'evaluation_date' => 'nullable|date',
            'days_on_stock' => 'nullable|integer|min:0',
            'buyer_id' => 'nullable|exists:buyers,customer_id',
            'seller_id' => 'nullable|exists:sellers,seller_id',
            'status' => 'sometimes|required|string|in:Available,Sold,Reserved',
            'customer_number' => 'nullable|exists:buyers,customer_number',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Set last_edited_date to current date
        $data = $request->all();
        $data['last_edited_date'] = now();

        try {
            DB::beginTransaction();

            $vehicle->update($data);

            DB::commit();

            return response()->json($vehicle);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update vehicle: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $vin)
    {
        $vehicle = Vehicle::findOrFail($vin);

        try {
            DB::beginTransaction();

            // Delete related records first
            $vehicle->engineSpecification()->delete();
            $vehicle->vehicleRegistration()->delete();
            $vehicle->damageRecords()->delete();
            $vehicle->tires()->delete();
            $vehicle->inspectionRecords()->delete();
            $vehicle->additionalEquipment()->delete();
            $vehicle->salesLogs()->delete();

            // Then delete the vehicle
            $vehicle->delete();

            DB::commit();

            return response()->json(['message' => 'Vehicle deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete vehicle: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all related data for vehicle form
     */
    public function getRelatedData()
    {
        $data = [
            'manufacturers' => Manufacturer::all(),
            'models' => VehicleModel::with('manufacturer')->get(),
            'transmissions' => Transmission::all(),
            'vehicleTypes' => VehicleType::all(),
            'driveTypes' => DriveType::all(),
            'ownerTypes' => OwnerType::all(),
            'buyers' => Buyer::all(),
            'sellers' => Seller::all(),
        ];

        return response()->json($data);
    }
}
