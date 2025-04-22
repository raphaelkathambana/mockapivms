<?php

namespace App\Http\Controllers;

use App\Models\Tire;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tires = Tire::with('vehicle')->get();
        return response()->json($tires);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'tire_brand' => 'required|string|max:255',
            'tire_model' => 'required|string|max:255',
            'tire_size' => 'required|string|max:50',
            'tire_type' => 'required|string|max:50',
            'production_date' => 'required|date',
            'purchase_date' => 'required|date',
            'tread_depth' => 'required|numeric|min:0|max:20',
            'position' => 'required|string|in:Front Left,Front Right,Rear Left,Rear Right,Spare',
            'condition' => 'required|string|in:New,Good,Fair,Poor,Needs Replacement',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $tire = Tire::create($request->all());

            DB::commit();

            return response()->json($tire, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create tire record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $tire = Tire::with('vehicle')->findOrFail($id);
        return response()->json($tire);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $tire = Tire::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'tire_brand' => 'sometimes|required|string|max:255',
            'tire_model' => 'sometimes|required|string|max:255',
            'tire_size' => 'sometimes|required|string|max:50',
            'tire_type' => 'sometimes|required|string|max:50',
            'production_date' => 'sometimes|required|date',
            'purchase_date' => 'sometimes|required|date',
            'tread_depth' => 'sometimes|required|numeric|min:0|max:20',
            'position' => 'sometimes|required|string|in:Front Left,Front Right,Rear Left,Rear Right,Spare',
            'condition' => 'sometimes|required|string|in:New,Good,Fair,Poor,Needs Replacement',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $tire->update($request->all());

            DB::commit();

            return response()->json($tire);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update tire record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $tire = Tire::findOrFail($id);

        try {
            DB::beginTransaction();

            $tire->delete();

            DB::commit();

            return response()->json(['message' => 'Tire record deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete tire record: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get tires by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $tires = Tire::where('vin', $vin)->get();
        return response()->json($tires);
    }

    /**
     * Get vehicles for tire form
     */
    public function getVehicles()
    {
        $vehicles = Vehicle::all();
        return response()->json($vehicles);
    }
}
