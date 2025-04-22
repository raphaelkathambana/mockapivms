<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelType extends Model
{
    use HasFactory;

    protected $primaryKey = 'fuel_type_id';

    protected $fillable = [
        'fuel_name',
    ];

    public function engineSpecifications(): HasMany
    {
        return $this->hasMany(EngineSpecification::class, 'fuel_type_id');
    }
}
