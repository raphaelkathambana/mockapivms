<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    protected $primaryKey = 'rule_id';

    protected $fillable = [
        'rule_data',
        'rejection_reason',
    ];

    protected $casts = [
        'rule_data' => 'json',
    ];
}
