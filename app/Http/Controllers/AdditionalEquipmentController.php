<?php

namespace App\Http\Controllers;

use App\Models\AdditionalEquipment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdditionalEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipment = AdditionalEquipment::with('vehicle')->get();
        return response()->json($equipment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'equipment_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $equipment = AdditionalEquipment::create($request->all());

            DB::commit();

            return response()->json($equipment, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create additional equipment record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $equipment = AdditionalEquipment::with('vehicle')->findOrFail($id);
        return response()->json($equipment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $equipment = AdditionalEquipment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'equipment_name' => 'sometimes|required|string|max:255',
            'equipment_description' => 'sometimes|required|string',
            'installation_date' => 'sometimes|required|date',
            'price' => 'sometimes|required|numeric|min:0',
            'warranty_expiry' => 'nullable|date|after:installation_date',
            'condition' => 'sometimes|required|string|in:New,Good,Fair,Poor',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $equipment->update($request->all());

            DB::commit();

            return response()->json($equipment);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update additional equipment record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $equipment = AdditionalEquipment::findOrFail($id);

        try {
            DB::beginTransaction();

            $equipment->delete();

            DB::commit();

            return response()->json(['message' => 'Additional equipment record deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete additional equipment record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get additional equipment by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $equipment = AdditionalEquipment::where('vin', $vin)->get();
        return response()->json($equipment);
    }

    /**
     * Get vehicles for additional equipment form
     */
    public function getVehicles()
    {
        $vehicles = Vehicle::all();
        return response()->json($vehicles);
    }
}
