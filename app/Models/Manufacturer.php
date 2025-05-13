<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    use HasFactory;

    protected $primaryKey = 'manufacturer_id';

    protected $fillable = [
        'name',
        'country_of_origin',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class, 'manufacturer_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'manufacturer_id');
    }
}
