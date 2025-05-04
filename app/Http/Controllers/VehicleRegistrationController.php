<?php

namespace App\Http\Controllers;

use App\Models\VehicleRegistration;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VehicleRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = VehicleRegistration::with('vehicle')->get();
        return response()->json($registrations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin' => 'required|string|exists:vehicles,vin',
            'custom_license_plate' => 'required|string|max:20|unique:vehicle_registrations',
            'delivery_date' => 'required|date',
            'sepa_data' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Check if registration already exists for this vehicle
            $existingRegistration = VehicleRegistration::where('vin', $request->vin)->first();
            if ($existingRegistration) {
                return response()->json(['error' => 'Registration already exists for this vehicle'], 422);
            }

            $registration = VehicleRegistration::create($request->all());

            DB::commit();

            return response()->json($registration, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create vehicle registration: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $registration = VehicleRegistration::with('vehicle')->findOrFail($id);
        return response()->json($registration);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $registration = VehicleRegistration::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vin' => 'sometimes|required|string|exists:vehicles,vin',
            'license_plate' => 'sometimes|required|string|max:20|unique:vehicle_registrations,license_plate,' . $id . ',registration_id',
            'registration_date' => 'sometimes|required|date',
            'expiry_date' => 'sometimes|required|date|after:registration_date',
            'registration_authority' => 'sometimes|required|string|max:255',
            'registration_document_number' => 'sometimes|required|string|max:50|unique:vehicle_registrations,registration_document_number,' . $id . ',registration_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If VIN is changing, check if registration already exists for the new vehicle
            if (isset($request->vin) && $request->vin !== $registration->vin) {
                $existingRegistration = VehicleRegistration::where('vin', $request->vin)->first();
                if ($existingRegistration) {
                    return response()->json(['error' => 'Registration already exists for this vehicle'], 422);
                }
            }

            $registration->update($request->all());

            DB::commit();

            return response()->json($registration);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update vehicle registration: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $registration = VehicleRegistration::findOrFail($id);

        try {
            DB::beginTransaction();

            $registration->delete();

            DB::commit();

            return response()->json(['message' => 'Vehicle registration deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete vehicle registration: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get vehicles without registration
     */
    public function getVehiclesWithoutRegistration()
    {
        $registeredVins = VehicleRegistration::pluck('vin')->toArray();
        $vehicles = Vehicle::whereNotIn('vin', $registeredVins)->get();

        return response()->json($vehicles);
    }

    /**
     * Get registration by vehicle
     */
    public function getByVehicle(string $vin)
    {
        $registration = VehicleRegistration::where('vin', $vin)->first();

        if (!$registration) {
            return response()->json(['error' => 'Registration not found for this vehicle'], 404);
        }

        return response()->json($registration);
    }
}
