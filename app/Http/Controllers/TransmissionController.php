<?php

namespace App\Http\Controllers;

use App\Models\Transmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transmissions = Transmission::all();
        return response()->json($transmissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:transmissions',
            'num_gears' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $transmission = Transmission::create($request->all());

            DB::commit();

            return response()->json($transmission, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create transmission: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $transmission = Transmission::with('vehicles')->findOrFail($id);
        return response()->json($transmission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $transmission = Transmission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type_name' => 'sometimes|required|string|max:255|unique:transmissions,type_name,' . $id . ',transmission_id',
            'num_gears' => 'sometimes|required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $transmission->update($request->all());

            DB::commit();

            return response()->json($transmission);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update transmission: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $transmission = Transmission::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if transmission has related vehicles
            if ($transmission->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete transmission with related vehicles. Please remove related records first.'], 422);
            }

            $transmission->delete();

            DB::commit();

            return response()->json(['message' => 'Transmission deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete transmission: ' . $e->getMessage()], 500);
        }
    }
}
