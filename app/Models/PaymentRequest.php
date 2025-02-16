<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'amount',               
        'reference_number',
        'from_bank',
        'to_bank',
        'payment_type',
        'pay_proof_img',
        'remarks',
        'is_approved',
        'approve_reject_remarks',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userId');
    }
    
    public function updatePaymentRequest($data,$id, $userId, $amount, $status)
    {
        if($status == 'APPROVE'){
            DB::table('users')->where('id',$userId)->update(['wallet' => \DB::raw("wallet + $amount")]);
        }
        DB::table('payment_requests')->where('id',$id)->update($data);
    }

}
