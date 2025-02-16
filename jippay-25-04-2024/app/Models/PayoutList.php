<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutList extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id','wallet_bal','name','ref_number','description','amount','bank_id','cus_name','accountNumber','bankIfsc','mobileNumber','beneBankName','transferMode','status','created_at','updated_at'
    ];
}
