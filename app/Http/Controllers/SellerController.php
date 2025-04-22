<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Address;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellers = Seller::with(['address', 'customerType'])->get();
        return response()->json($sellers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'address_id' => 'required|exists:addresses,address_id',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'customer_type_id' => 'required|exists:customer_types,customer_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $seller = Seller::create($request->all());

            DB::commit();

            return response()->json($seller, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create seller: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $seller = Seller::with(['address', 'customerType', 'vehicles'])->findOrFail($id);
        return response()->json($seller);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $seller = Seller::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string|in:Male,Female,Other',
            'address_id' => 'sometimes|required|exists:addresses,address_id',
            'phone_number' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
            'customer_type_id' => 'sometimes|required|exists:customer_types,customer_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $seller->update($request->all());

            DB::commit();

            return response()->json($seller);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update seller: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $seller = Seller::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if seller has related records
            if ($seller->vehicles()->count() > 0) {
                return response()->json(['error' => 'Cannot delete seller with related vehicles. Please remove related records first.'], 422);
            }

            $seller->delete();

            DB::commit();

            return response()->json(['message' => 'Seller deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete seller: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get related data for seller form
     */
    public function getRelatedData()
    {
        $data = [
            'addresses' => Address::all(),
            'customerTypes' => CustomerType::all(),
        ];

        return response()->json($data);
    }
}
