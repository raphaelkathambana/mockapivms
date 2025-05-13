<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalEquipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'equipment_id';
    protected $table = 'additional_equipment';

    protected $fillable = [
        'vin',
        'equipment_description',
        'condition',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin');
    }
}
