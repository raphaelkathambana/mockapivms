<?php

namespace App\Http\Controllers;

use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FuelTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fuelTypes = FuelType::all();
        return response()->json($fuelTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:fuel_types',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $fuelType = FuelType::create($request->all());

            DB::commit();

            return response()->json($fuelType, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create fuel type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $fuelType = FuelType::with('engineSpecifications')->findOrFail($id);
        return response()->json($fuelType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $fuelType = FuelType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:fuel_types,type_name,' . $id . ',fuel_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $fuelType->update($request->all());

            DB::commit();

            return response()->json($fuelType);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update fuel type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $fuelType = FuelType::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if fuel type has related engine specifications
            if ($fuelType->engineSpecifications()->count() > 0) {
                return response()->json(['error' => 'Cannot delete fuel type with related engine specifications. Please remove related records first.'], 422);
            }

            $fuelType->delete();

            DB::commit();

            return response()->json(['message' => 'Fuel type deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete fuel type: ' . $e->getMessage()], 500);
        }
    }
}
