<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'start_amount',
        'end_amount',
        'payout_charge',
        'payin_charge',
        'payin_charge_type',
        'payout_charge_type',        
        'created_by',
        'updated_by',
    ];

    public function getUserCharge()
    {
        
    }

}
