<?php

namespace App\Http\Controllers;

use App\Models\EngineSpecification;
use App\Models\Vehicle;
use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EngineSpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specs = EngineSpecification::with(['vehicle', 'fuelType'])->get();
        return response()->json($specs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'kw' => 'numeric|min:0',
            'hp' => 'numeric|min:0',
            'ccm' => 'required|integer|min:0',
            'fuel_type_id' => 'required|exists:fuel_types,fuel_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Check if engine specification already exists for this vehicle
            $existingSpec = EngineSpecification::where('vin', $request->vin)->first();
            if ($existingSpec) {
                return response()->json(['error' => 'Engine specification already exists for this vehicle'], 422);
            }

            $spec = EngineSpecification::create($request->all());

            DB::commit();

            return response()->json($spec, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create engine specification: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $spec = EngineSpecification::with(['vehicle', 'fuelType'])->findOrFail($id);
        return response()->json($spec);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $spec = EngineSpecification::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'engine_number' => 'sometimes|required|string|max:50|unique:engine_specifications,engine_number,' . $id . ',spec_id',
            'engine_capacity' => 'sometimes|required|numeric|min:0',
            'power_output' => 'sometimes|required|integer|min:0',
            'fuel_type_id' => 'sometimes|required|exists:fuel_types,fuel_type_id',
            'emission_class' => 'sometimes|required|string|max:50',
            'co2_emission' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If VIN is changing, check if engine specification already exists for the new vehicle
            if (isset($request->vin) && $request->vin !== $spec->vin) {
                $existingSpec = EngineSpecification::where('vin', $request->vin)->first();
                if ($existingSpec) {
                    return response()->json(['error' => 'Engine specification already exists for this vehicle'], 422);
                }
            }

            $spec->update($request->all());

            DB::commit();

            return response()->json($spec);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update engine specification: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $spec = EngineSpecification::findOrFail($id);

        try {
            DB::beginTransaction();

            $spec->delete();

            DB::commit();

            return response()->json(['message' => 'Engine specification deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete engine specification: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get related data for engine specification form
     */
    public function getRelatedData()
    {
        $data = [
            'vehicles' => Vehicle::all(),
            'fuelTypes' => FuelType::all(),
        ];

        return response()->json($data);
    }

    /**
     * Get engine specification by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $spec = EngineSpecification::with('fuelType')->where('vin', $vin)->first();

        if (!$spec) {
            return response()->json(['error' => 'Engine specification not found for this vehicle'], 404);
        }

        return response()->json($spec);
    }
}
