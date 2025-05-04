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
            'tread_depth' => 'required|numeric|min:0|max:20',
            'rim_type' => 'required|string|in:Steel,Aluminum,Alloy,Magnesium,Carbon Fiber',
            'position' => 'required|string|in:left-front,right-front,left-back,right-back',
            'tire_type' => 'required|string|in:summer,winter,all-season',
            'rim_status' => 'required|string|in:aftermarket,original',
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
            'tread_depth' => 'sometimes|required|numeric|min:0|max:20',
            'rim_type' => 'sometimes|required|string|in:Steel,Aluminum,Alloy,Magnesium,Carbon Fiber',
            'position' => 'sometimes|required|string|in:left-front,right-front,left-back,right-back',
            'tire_type' => 'sometimes|required|string|in:summer,winter,all-season',
            'rim_status' => 'sometimes|required|string|in:aftermarket,original',
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
