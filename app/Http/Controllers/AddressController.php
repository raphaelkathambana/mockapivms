<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:20',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $address = Address::create($request->all());

            DB::commit();

            return response()->json($address, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create address: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $address = Address::with(['buyers', 'sellers'])->findOrFail($id);
        return response()->json($address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $address = Address::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'street' => 'sometimes|required|string|max:255',
            'house_number' => 'sometimes|required|string|max:20',
            'postal_code' => 'sometimes|required|string|max:20',
            'city' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $address->update($request->all());

            DB::commit();

            return response()->json($address);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update address: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $address = Address::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if address has related records
            if ($address->buyers()->count() > 0 || $address->sellers()->count() > 0) {
                return response()->json(['error' => 'Cannot delete address with related records. Please remove related records first.'], 422);
            }

            $address->delete();

            DB::commit();

            return response()->json(['message' => 'Address deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete address: ' . $e->getMessage()], 500);
        }
    }
}
