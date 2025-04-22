<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerTypeController extends Controller
{
    public function index()
    {
        $customerTypes = CustomerType::all();
        return response()->json($customerTypes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:customer_types',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customerType = CustomerType::create($request->all());

        return response()->json($customerType, 201);
    }

    public function show(CustomerType $customerType)
    {
        return response()->json($customerType);
    }

    public function update(Request $request, CustomerType $customerType)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:255|unique:customer_types,type_name,' . $customerType->customer_type_id . ',customer_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customerType->update($request->all());

        return response()->json($customerType);
    }

    public function destroy(CustomerType $customerType)
    {
        $customerType->delete();

        return response()->json(['message' => 'Customer type deleted successfully'], 200);
    }
}
