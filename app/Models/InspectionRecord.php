<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'inspection_id';

    protected $fillable = [
        'vin',
        'inspection_date',
        'mileage_at_inspection',
        'service_details',
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin');
    }
}
