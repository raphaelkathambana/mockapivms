<?php

use App\Http\Controllers\ProcurementContractController;
use App\Http\Controllers\PurchaseLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\OwnerTypeController;
use App\Http\Controllers\FuelTypeController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\TransmissionController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\DriveTypeController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PurchaseContractController;
use App\Http\Controllers\VehicleRegistrationController;
use App\Http\Controllers\EngineSpecificationController;
use App\Http\Controllers\DamageRecordController;
use App\Http\Controllers\TireController;
use App\Http\Controllers\InspectionRecordController;
use App\Http\Controllers\AdditionalEquipmentController;
use App\Http\Controllers\SalesLogController;
use App\Http\Controllers\EmployeeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Items API (Legacy)
Route::apiResource('items', ItemController::class);

// Reference Data APIs
Route::apiResource('customer-types', CustomerTypeController::class);
Route::apiResource('owner-types', OwnerTypeController::class);
Route::apiResource('fuel-types', FuelTypeController::class);
Route::apiResource('manufacturers', ManufacturerController::class);
Route::apiResource('transmissions', TransmissionController::class);
Route::apiResource('vehicle-types', VehicleTypeController::class);
Route::apiResource('drive-types', DriveTypeController::class);

// Address API
Route::apiResource('addresses', AddressController::class);
Route::get('addresses/by-city/{city}', [AddressController::class, 'getByCity']);

// Blacklist API
Route::apiResource('blacklists', BlacklistController::class);
Route::get('blacklists/check/{name}/{identification}', [BlacklistController::class, 'checkBlacklisted']);

// Vehicle Model API
Route::apiResource('vehicle-models', VehicleModelController::class);
Route::get('vehicle-models/by-manufacturer/{manufacturer_id}', [VehicleModelController::class, 'getModelsByManufacturer']);

// Buyer API
Route::apiResource('buyers', BuyerController::class);
Route::get('buyers/with-address', [BuyerController::class, 'getBuyersWithAddress']);
Route::get('buyers/by-customer-type/{customer_type_id}', [BuyerController::class, 'getByCustomerType']);

// Seller API
Route::apiResource('sellers', SellerController::class);
Route::get('sellers/with-address', [SellerController::class, 'getSellersWithAddress']);
Route::get('sellers/by-customer-type/{customer_type_id}', [SellerController::class, 'getByCustomerType']);

// Vehicle API
Route::apiResource('vehicles', VehicleController::class);
Route::get('vehicles/available', [VehicleController::class, 'getAvailableVehicles']);
Route::get('vehicles/by-manufacturer/{manufacturer_id}', [VehicleController::class, 'getByManufacturer']);
Route::get('vehicles/by-model/{model_id}', [VehicleController::class, 'getByModel']);
Route::get('vehicles/by-year/{year}', [VehicleController::class, 'getByYear']);
Route::get('vehicles/by-price-range/{min}/{max}', [VehicleController::class, 'getByPriceRange']);

// Purchase Contract API
Route::apiResource('purchase-contracts', PurchaseContractController::class);
Route::get('purchase-contracts/by-buyer/{buyer_id}', [PurchaseContractController::class, 'getByBuyer']);
Route::get('purchase-contracts/by-seller/{seller_id}', [PurchaseContractController::class, 'getBySeller']);
Route::get('purchase-contracts/by-vehicle/{vin}', [PurchaseContractController::class, 'getByVehicle']);
Route::get('purchase-contracts/by-employee/{employee_id}', [PurchaseContractController::class, 'getByEmployee']);

// Vehicle Registration API
Route::apiResource('vehicle-registrations', VehicleRegistrationController::class);
Route::get('vehicle-registrations/by-vehicle/{vin}', [VehicleRegistrationController::class, 'getByVehicle']);
Route::get('vehicle-registrations/vehicles-without-registration', [VehicleRegistrationController::class, 'getVehiclesWithoutRegistration']);

// Engine Specification API
Route::apiResource('engine-specifications', EngineSpecificationController::class);
Route::get('engine-specifications/by-vehicle/{vin}', [EngineSpecificationController::class, 'getByVehicle']);

// Damage Record API
Route::apiResource('damage-records', DamageRecordController::class);
Route::get('damage-records/by-vehicle/{vin}', [DamageRecordController::class, 'getByVehicle']);

// Tire API
Route::apiResource('tires', TireController::class);
Route::get('tires/by-vehicle/{vin}', [TireController::class, 'getByVehicle']);

// Inspection Record API
Route::apiResource('inspection-records', InspectionRecordController::class);
Route::get('inspection-records/by-vehicle/{vin}', [InspectionRecordController::class, 'getByVehicle']);

// Additional Equipment API
Route::apiResource('additional-equipment', AdditionalEquipmentController::class);
Route::get('additional-equipment/by-vehicle/{vin}', [AdditionalEquipmentController::class, 'getByVehicle']);
Route::get('additional-equipment/vehicles', [AdditionalEquipmentController::class, 'getVehicles']);

// Sales Log API
Route::apiResource('sales-logs', SalesLogController::class);
Route::get('sales-logs/by-vehicle/{vin}', [SalesLogController::class, 'getByVehicle']);
Route::get('sales-logs/by-employee/{employee_id}', [SalesLogController::class, 'getByEmployee']);

// Employee API
Route::apiResource('employees', EmployeeController::class);
Route::get('employees/active', [EmployeeController::class, 'getActiveEmployees']);

Route::apiResource('procurement-contracts', ProcurementContractController::class);
Route::get('procurement-contracts/by-seller/{seller_id}', [ProcurementContractController::class, 'getBySeller']);
Route::get('procurement-contracts/by-vehicle/{vin}', [ProcurementContractController::class, 'getByVehicle']);
Route::get('procurement-contracts/by-employee/{employee_id}', [ProcurementContractController::class, 'getByEmployee']);
Route::get('procurement-contracts-related-data', [ProcurementContractController::class, 'getRelatedData']);

// Purchase Log API
Route::apiResource('purchase-logs', PurchaseLogController::class);
Route::get('purchase-logs/by-vehicle/{vin}', [PurchaseLogController::class, 'getByVehicle']);
Route::get('purchase-logs/by-seller/{seller_id}', [PurchaseLogController::class, 'getBySeller']);
Route::get('purchase-logs/by-employee/{employee_id}', [PurchaseLogController::class, 'getByEmployee']);
Route::get('purchase-logs-related-data', [PurchaseLogController::class, 'getRelatedData']);
