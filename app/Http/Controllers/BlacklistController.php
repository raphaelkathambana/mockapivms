<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BlacklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blacklists = Blacklist::all();
        return response()->json($blacklists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'reason' => 'required|string',
            'date_added' => 'required|date',
            'added_by' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $blacklist = Blacklist::create($request->all());

            DB::commit();

            return response()->json($blacklist, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create blacklist entry: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $blacklist = Blacklist::findOrFail($id);
        return response()->json($blacklist);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $blacklist = Blacklist::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'reason' => 'sometimes|required|string',
            'date_added' => 'sometimes|required|date',
            'added_by' => 'sometimes|required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $blacklist->update($request->all());

            DB::commit();

            return response()->json($blacklist);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update blacklist entry: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $blacklist = Blacklist::findOrFail($id);

        try {
            DB::beginTransaction();

            $blacklist->delete();

            DB::commit();

            return response()->json(['message' => 'Blacklist entry deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete blacklist entry: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if a person is blacklisted
     */
    public function checkBlacklisted(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $blacklisted = Blacklist::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->first();

        return response()->json([
            'blacklisted' => $blacklisted ? true : false,
            'details' => $blacklisted
        ]);
    }
}
