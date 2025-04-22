<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $primaryKey = 'vin';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'vin',
        'manufacturer_id',
        'model_id',
        'first_registration',
        'mileage',
        'transmission_id',
        'type_id',
        'drive_type_id',
        'color',
        'purchase_price',
        'selling_price',
        'num_previous_owners',
        'owner_type_id',
        'general_inspection_next_due_date',
        'evaluation_date',
        'last_edited_date',
        'days_on_stock',
        'buyer_id',
        'seller_id',
        'status',
        'customer_number',
        'additional_info',
    ];

    protected $casts = [
        'first_registration' => 'date',
        'general_inspection_next_due_date' => 'date',
        'evaluation_date' => 'date',
        'last_edited_date' => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo(Transmission::class, 'transmission_id');
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function driveType(): BelongsTo
    {
        return $this->belongsTo(DriveType::class, 'drive_type_id');
    }

    public function ownerType(): BelongsTo
    {
        return $this->belongsTo(OwnerType::class, 'owner_type_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function purchaseContract(): HasOne
    {
        return $this->hasOne(PurchaseContract::class, 'vin');
    }

    public function vehicleRegistration(): HasOne
    {
        return $this->hasOne(VehicleRegistration::class, 'vin');
    }

    public function engineSpecification(): HasOne
    {
        return $this->hasOne(EngineSpecification::class, 'vin');
    }

    public function damageRecords(): HasMany
    {
        return $this->hasMany(DamageRecord::class, 'vin');
    }

    public function tires(): HasMany
    {
        return $this->hasMany(Tire::class, 'vin');
    }

    public function inspectionRecords(): HasMany
    {
        return $this->hasMany(InspectionRecord::class, 'vin');
    }

    public function additionalEquipment(): HasMany
    {
        return $this->hasMany(AdditionalEquipment::class, 'vin');
    }

    public function salesLogs(): HasMany
    {
        return $this->hasMany(SalesLog::class, 'vin');
    }
}
