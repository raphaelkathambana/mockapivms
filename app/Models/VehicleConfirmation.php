<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleConfirmation extends Model
{
    use HasFactory;

    protected $primaryKey = 'confirmation_id';

    protected $fillable = [
        'vin',
        'num_previous_owners',
        'warranty_status',
        'inspection_status',
        'seller_id',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin', 'vin');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
