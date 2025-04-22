<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleTypes = VehicleType::all();
        return response()->json($vehicleTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:vehicle_types',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $vehicleType = VehicleType::create($request->all());

            DB::commit();

            return response()->json($vehicleType, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create vehicle type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $vehicleType = VehicleType::with('vehicles')->findOrFail($id);
        return response()->json($vehicleType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:vehicle_types,type_name,' . $id . ',type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $vehicleType->update($request->all());

            DB::commit();

            return response()->json($vehicleType);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update vehicle type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if vehicle type has related vehicles
            if ($vehicleType->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete vehicle type with related vehicles. Please remove related records first.'], 422);
            }

            $vehicleType->delete();

            DB::commit();

            return response()->json(['message' => 'Vehicle type deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete vehicle type: ' . $e->getMessage()], 500);
        }
    }
}
