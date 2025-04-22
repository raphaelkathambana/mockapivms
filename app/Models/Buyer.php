<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_number',
        'first_name',
        'last_name',
        'address_id',
        'phone_number',
        'email',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'buyer_id');
    }

    public function purchaseContracts(): HasMany
    {
        return $this->hasMany(PurchaseContract::class, 'customer_id');
    }

    public function salesLogs(): HasMany
    {
        return $this->hasMany(SalesLog::class, 'customer_number', 'customer_number');
    }
}
