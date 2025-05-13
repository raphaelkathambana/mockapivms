<?php

namespace App\Http\Controllers;

use App\Models\AdditionalEquipment;
use App\Models\DamageRecord;
use App\Models\Tire;
use App\Models\Vehicle;
use App\Models\VehicleConfirmation;
use DB;
use Illuminate\Http\Request;
use Validator;

class VehicleConfirmationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_vin' => 'required|string|exists:vehicles,vin',
            'vin' => 'required|string',
            'num_previous_owners' => 'required|integer|min:0',
            'warranty_status' => 'required|string',
            'inspection_status' => 'required|string',
            'seller_id' => 'required|exists:sellers,seller_id',
            'tires_information' => 'required|array',
            'tires_information.*.position' => 'required|string|in:left-front,right-front,left-back,right-back',
            'tires_information.*.rim_status' => 'required|string|in:aftermarket,original',
            'tires_information.*.rim_type' => 'required|string|in:Steel,Aluminum,Alloy,Magnesium,Carbon Fiber',
            'tires_information.*.tire_type' => 'required|string|in:summer,winter,all-season',
            'tires_information.*.tread_depth' => 'required|numeric|min:0|max:20',
            'damages' => 'array',
            'damages.*.description' => 'required|string',
            'damages.*.cost' => 'numeric|min:0',
            'damages.*.damage_type' => 'required|string|in:Dent,Scruff,Damage',
            'damages.*.location' => 'required|string',
            'equipment' => 'array',
            'equipment.*.name' => 'required|string',
            'equipment.*.condition' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Update vehicle information
            $vehicle = Vehicle::findOrFail($request->original_vin);
            $vehicle->update([
                'vin' => $request->vin,
                'num_previous_owners' => $request->num_previous_owners,
                'seller_id' => $request->seller_id
            ]);

            // Create confirmation record
            $confirmation = VehicleConfirmation::create([
                'vin' => $request->vin,
                'num_previous_owners' => $request->num_previous_owners,
                'warranty_status' => $request->warranty_status,
                'inspection_status' => $request->inspection_status,
                'seller_id' => $request->seller_id
            ]);

            // Add tire information
            foreach ($request->tires_information as $tireInfo) {
                Tire::create([
                    'vin' => $request->vin,
                    'position' => $tireInfo['position'],
                    'tread_depth' => $tireInfo['tread_depth'],
                    'rim_type' => $tireInfo['rim_type'],
                    'tire_type' => $tireInfo['tire_type'],
                    'rim_status' => $tireInfo['rim_status']
                ]);
            }

            // Add damage records if any
            if (!empty($request->damages)) {
                foreach ($request->damages as $damage) {
                    DamageRecord::create([
                        'vin' => $request->vin,
                        'description' => $damage['description'],
                        'damage_type' => $damage['damage_type'],
                        'location' => $damage['location'],
                        'cost' => $damage['cost'],
                    ]);
                }
            }

            // Add additional equipment if any
            if (!empty($request->equipment)) {
                foreach ($request->equipment as $equipment) {
                    AdditionalEquipment::create([
                        'vin' => $request->vin,
                        'equipment_description' => $equipment['name'],
                        'condition' => $equipment['condition']
                    ]);
                }
            }

            // change status to 'Available'
            $vehicle->update(['status' => 'Available']);

            DB::commit();

            return response()->json($confirmation, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save DATA SADLY confirmation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
