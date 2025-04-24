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
    /**
     * Get the sales logs for the employee.
     */
    public function salesLogs(): HasMany
    {
        return $this->hasMany(SalesLog::class, 'employee_id');
    }

    /**
     * Get the purchase logs for the employee.
     */
    public function purchaseLogs(): HasMany
    {
        return $this->hasMany(PurchaseLog::class, 'employee_id');
    }

    /**
     * Get the purchase contracts associated with the employee.
     */
    public function purchaseContracts()
    {
        return $this->hasMany(PurchaseContract::class, 'employee_id');
    }

    /**
     * Get the procurement contracts associated with the employee.
     */
    public function procurementContracts()
    {
        return $this->hasMany(ProcurementContract::class, 'employee_id');
    }
}
