<?php

namespace App\Http\Controllers;

use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DriveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $driveTypes = DriveType::all();
        return response()->json($driveTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:drive_types',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $driveType = DriveType::create($request->all());

            DB::commit();

            return response()->json($driveType, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create drive type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $driveType = DriveType::with('vehicles')->findOrFail($id);
        return response()->json($driveType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $driveType = DriveType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:drive_types,type_name,' . $id . ',drive_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $driveType->update($request->all());

            DB::commit();

            return response()->json($driveType);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update drive type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $driveType = DriveType::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if drive type has related vehicles
            if ($driveType->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete drive type with related vehicles. Please remove related records first.'], 422);
            }

            $driveType->delete();

            DB::commit();

            return response()->json(['message' => 'Drive type deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete drive type: ' . $e->getMessage()], 500);
        }
    }
}
