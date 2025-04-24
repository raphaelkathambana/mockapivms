<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    protected $primaryKey = 'seller_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'address_id',
        'phone_number',
        'email',
        'customer_type_id',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function customerType(): BelongsTo
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'seller_id');
    }
    public function procurementContracts(): HasMany
    {
        return $this->hasMany(ProcurementContract::class, 'seller_id');
    }
}
