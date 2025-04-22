<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transmission extends Model
{
    use HasFactory;

    protected $primaryKey = 'transmission_id';

    protected $fillable = [
        'type',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'transmission_id');
    }
}
