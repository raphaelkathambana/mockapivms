<?php

namespace App\Http\Controllers;

use App\Models\OwnerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OwnerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownerTypes = OwnerType::all();
        return response()->json($ownerTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:owner_types',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $ownerType = OwnerType::create($request->all());

            DB::commit();

            return response()->json($ownerType, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create owner type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $ownerType = OwnerType::with('vehicles')->findOrFail($id);
        return response()->json($ownerType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $ownerType = OwnerType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:owner_types,type_name,' . $id . ',owner_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $ownerType->update($request->all());

            DB::commit();

            return response()->json($ownerType);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update owner type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $ownerType = OwnerType::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if owner type has related vehicles
            if ($ownerType->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete owner type with related vehicles. Please remove related records first.'], 422);
            }

            $ownerType->delete();

            DB::commit();

            return response()->json(['message' => 'Owner type deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete owner type: ' . $e->getMessage()], 500);
        }
    }
}
