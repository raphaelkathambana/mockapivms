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
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'employee_id' => $this->employee_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->role,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            // 'created_at' => $this->created_at, // Optionally include timestamps
            // 'updated_at' => $this->updated_at, // Optionally include timestamps
        ];
    }

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
