<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'address_id';

    protected $fillable = [
        'street',
        'house_number',
        'city',
        'postal_code',
        'country',
    ];

    public function buyers(): HasMany
    {
        return $this->hasMany(Buyer::class, 'address_id');
    }

    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class, 'address_id');
    }
}
