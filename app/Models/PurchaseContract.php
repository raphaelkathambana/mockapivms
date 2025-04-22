<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseContract extends Model
{
    use HasFactory;

    protected $primaryKey = 'contract_id';

    protected $fillable = [
        'vin',
        'customer_id',
        'employee_id',
        'contract_date',
        'digital_signature',
    ];

    protected $casts = [
        'contract_date' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'customer_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
