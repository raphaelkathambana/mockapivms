<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $employee = Employee::create($request->all());

            DB::commit();

            return response()->json($employee, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create employee: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $employee = Employee::with([
            'purchaseContracts.vehicle.manufacturer',
            'purchaseContracts.vehicle.model',
            'purchaseContracts.buyer',
            'procurementContracts.vehicle.manufacturer',
            'procurementContracts.vehicle.model',
            'procurementContracts.seller'
        ])->findOrFail($id);
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $id . ',employee_id',
            'phone_number' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $employee->update($request->all());

            DB::commit();

            return response()->json($employee);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update employee: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $employee = Employee::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if employee has related records
            if ($employee->salesLogs()->count() > 0 || $employee->purchaseContracts()->count() > 0) {
                return response()->json(['error' => 'Cannot delete employee with related records. Please remove related records first.'], 422);
            }

            $employee->delete();

            DB::commit();

            return response()->json(['message' => 'Employee deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete employee: ' . $e->getMessage()], 500);
        }
    }
}
