<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'email',
        'phone_number',
    ];

    public function salesLogs(): HasMany
    {
        return $this->hasMany(SalesLog::class, 'employee_id');
    }

    public function purchaseContracts(): HasMany
    {
        return $this->hasMany(PurchaseContract::class, 'employee_id');
    }
}
