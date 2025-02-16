<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'txnId',               
        'orderId',
        'type',
        'operator',
        'openBalance',
        'amount',
        'walletBalance',
        'credit',
        'debit',               
        'remark',
        'api',
        'refundId',
        'status',
        'requestIp',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function getWalletDataAjax($start,$length,$date="",$search="",$userId=""){

        $selectedColumns = ['user_transactions.orderId','user_transactions.type','user_transactions.openBalance','user_transactions.amount','user_transactions.walletBalance','user_transactions.remark','user_transactions.created_at','user_transactions.status','users.name'];
        $query = UserTransaction::select($selectedColumns)->join('users', 'user_transactions.userId', '=', 'users.id');
        if($date != ""){
            $query->whereDate('user_transactions.created_at',$date);
        }
        if($userId != ""){
            if (is_array($userId)){
                $query->whereIn('user_transactions.userId',$userId);
            }else{
                $query->where('user_transactions.userId',$userId);
            }             
        }
        if($search != ""){
            $query->where('user_transactions.txnId', $search)->orWhere('user_transactions.orderId', $search);
        }
        return $query->skip($start)->take($length)->orderBy('user_transactions.id','desc');
    }

    public function getChargebackDataAjax($start,$length,$date="",$search="",$userId=""){

        $selectedColumns = ['user_transactions.orderId','user_transactions.type','user_transactions.openBalance','user_transactions.amount','user_transactions.walletBalance','user_transactions.remark','user_transactions.created_at','user_transactions.status','users.name'];
        $query = UserTransaction::select($selectedColumns)->join('users', 'user_transactions.userId', '=', 'users.id')->where('user_transactions.type',"CHARGEBACK");
        if($date != ""){
            $query->whereDate('user_transactions.created_at',$date);
        }
        if($userId != ""){
            if (is_array($userId)){
                $query->whereIn('user_transactions.userId',$userId);
            }else{
                $query->where('user_transactions.userId',$userId);
            }             
        }
        if($search != ""){
            $query->where('user_transactions.txnId', $search)->orWhere('user_transactions.orderId', $search);
        }
        return $query->skip($start)->take($length)->orderBy('user_transactions.id','desc');
    }

    public function updateUserTxnData($data,$txnId)
    {
        DB::table('user_transactions')
        ->where('txnId',$txnId)
        ->update($data);
    }

    public function updateUserTxnDataByOrderid($data,$txnId)
    {
        DB::table('user_transactions')
        ->where('orderId',$txnId)
        ->update($data);
    }

    public function updateUserOrderIdData($data,$orderId)
    {
        DB::table('user_transactions')
        ->where('orderId',$orderId)
        ->update($data);
    }

}
