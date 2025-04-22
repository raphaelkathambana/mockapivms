<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tire extends Model
{
    use HasFactory;

    protected $primaryKey = 'tire_id';

    protected $fillable = [
        'vin',
        'tread_depth',
        'rim_type',
    ];

    protected $casts = [
        'tread_depth' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin');
    }
}
