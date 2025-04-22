<?php

namespace App\Http\Controllers;

use App\Models\VehicleModel;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = VehicleModel::with('manufacturer')->get();
        return response()->json($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model_name' => 'required|string|max:255',
            'manufacturer_id' => 'required|exists:manufacturers,manufacturer_id',
            'year_from' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'year_to' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $model = VehicleModel::create($request->all());

            DB::commit();

            return response()->json($model, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create vehicle model: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $model = VehicleModel::with(['manufacturer', 'vehicles'])->findOrFail($id);
        return response()->json($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $model = VehicleModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'model_name' => 'sometimes|required|string|max:255',
            'manufacturer_id' => 'sometimes|required|exists:manufacturers,manufacturer_id',
            'year_from' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
            'year_to' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $model->update($request->all());

            DB::commit();

            return response()->json($model);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update vehicle model: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $model = VehicleModel::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if model has related vehicles
            if ($model->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete vehicle model with related vehicles. Please remove related records first.'], 422);
            }

            $model->delete();

            DB::commit();

            return response()->json(['message' => 'Vehicle model deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete vehicle model: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get models by manufacturer
     */
    public function getModelsByManufacturer(int $manufacturerId)
    {
        $models = VehicleModel::where('manufacturer_id', $manufacturerId)->get();
        return response()->json($models);
    }

    /**
     * Get manufacturers for model form
     */
    public function getManufacturers()
    {
        $manufacturers = Manufacturer::all();
        return response()->json($manufacturers);
    }
}
