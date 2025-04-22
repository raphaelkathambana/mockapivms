<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerType extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_type_id';

    protected $fillable = [
        'type_name',
    ];

    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class, 'customer_type_id');
    }
}
