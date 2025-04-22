<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buyers = Buyer::with('address')->get();
        return response()->json($buyers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_number' => 'required|string|max:50|unique:buyers,customer_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_id' => 'required|exists:addresses,address_id',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $buyer = Buyer::create($request->all());

            DB::commit();

            return response()->json($buyer, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create buyer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $buyer = Buyer::with(['address', 'vehicles', 'purchaseContracts', 'salesLogs'])->findOrFail($id);
        return response()->json($buyer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $buyer = Buyer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'customer_number' => 'sometimes|required|string|max:50|unique:buyers,customer_number,' . $id . ',customer_id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'address_id' => 'sometimes|required|exists:addresses,address_id',
            'phone_number' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $buyer->update($request->all());

            DB::commit();

            return response()->json($buyer);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update buyer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $buyer = Buyer::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if buyer has related records
            if ($buyer->vehicles()->count() > 0 || $buyer->purchaseContracts()->count() > 0 || $buyer->salesLogs()->count() > 0) {
                return response()->json(['error' => 'Cannot delete buyer with related records. Please remove related records first.'], 422);
            }

            $buyer->delete();

            DB::commit();

            return response()->json(['message' => 'Buyer deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete buyer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all addresses for buyer form
     */
    public function getAddresses()
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }
}
