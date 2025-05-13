<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementContract extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'contract_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vin',
        'seller_id',
        'employee_id',
        'contract_date',
        'digital_signature',
        'purchase_amount',
        'signature',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'contract_date' => 'date',
        'purchase_amount' => 'decimal:2',
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'contract_id' => $this->contract_id,
            'vin' => $this->vin,
            'seller_id' => $this->seller_id,
            'employee_id' => $this->employee_id,
            'contract_date' => $this->contract_date ? $this->contract_date->toDateString() : null,
            'purchase_amount' => $this->purchase_amount,
            // 'digital_signature' => $this->digital_signature, // Potentially large, include if needed
            // 'signature' => $this->signature, // Potentially large, include if needed
            'vehicle' => $this->vehicle ? $this->vehicle->toArray() : null,
            'seller' => $this->seller ? $this->seller->toArray() : null,
            'employee' => $this->employee ? $this->employee->toArray() : null,
            // 'created_at' => $this->created_at, // Optionally include timestamps
            // 'updated_at' => $this->updated_at, // Optionally include timestamps
        ];
    }

    /**
     * Get the vehicle that the contract is for.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin', 'vin');
    }

    /**
     * Get the seller who signed the contract.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    /**
     * Get the employee who processed the contract.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
