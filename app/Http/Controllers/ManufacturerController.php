<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manufacturers = Manufacturer::all();
        return response()->json($manufacturers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:manufacturers',
            'country_of_origin' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $manufacturer = Manufacturer::create($request->all());

            DB::commit();

            return response()->json($manufacturer, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create manufacturer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $manufacturer = Manufacturer::with(['models', 'vehicles'])->findOrFail($id);
        return response()->json($manufacturer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:manufacturers,name,' . $id . ',manufacturer_id',
            'country_of_origin' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $manufacturer->update($request->all());

            DB::commit();

            return response()->json($manufacturer);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update manufacturer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if manufacturer has related records
            if ($manufacturer->models()->count() > 0 || $manufacturer->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete manufacturer with related records. Please remove related records first.'], 422);
            }

            $manufacturer->delete();

            DB::commit();

            return response()->json(['message' => 'Manufacturer deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete manufacturer: ' . $e->getMessage()], 500);
        }
    }
}
