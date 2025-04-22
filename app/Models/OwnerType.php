<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OwnerType extends Model
{
    use HasFactory;

    protected $primaryKey = 'owner_type_id';

    protected $fillable = [
        'type_name',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'owner_type_id');
    }
}
