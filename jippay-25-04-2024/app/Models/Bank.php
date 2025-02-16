<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id','cus_name','acc_number','mobile_no','ifsc_code','pincode','bank_name','payment_type','created_at','updated_at'
    ];
}
