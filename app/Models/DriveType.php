<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriveType extends Model
{
    use HasFactory;

    protected $primaryKey = 'drive_type_id';

    protected $fillable = [
        'drive_type_name',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'drive_type_id');
    }
}
