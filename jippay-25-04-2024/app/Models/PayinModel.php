<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PayinModel extends Model
{
    use HasFactory;
    protected $table = 'payin_transactions';

    protected $fillable = [
        'userId',
        'txnId',               
        'orderId',
        'contactId',
        'amount',
        'charge',   
        'gst',            
        'totalAmount',
        'payerName',
        'payerMobile',
        'payerVa',
        'utr',
        'api',
        'status',
        'IpAddress',
    ];

    public function updatePayInData($data,$txnId)
    {
        DB::table('payin_transactions')
        ->where('txnId',$txnId)
        ->update($data);
    }

    public function updatePayInDataByRefId($data,$txnId)
    {
        DB::table('payin_transactions')
        ->where('orderId',$txnId)
        ->update($data);
    }

    public function getWalletDataAjax($start,$length,$date="",$search="",$userId=""){

        $selectedColumns = ['payin_transactions.orderId','payin_transactions.txnId','payin_transactions.amount','payin_transactions.charge','payin_transactions.gst','payin_transactions.totalAmount','payin_transactions.utr','payin_transactions.status','payin_transactions.created_at','users.name'];
        $query = PayinModel::select($selectedColumns)->join('users', 'payin_transactions.userId', '=', 'users.id');
        if($date != ""){
            $query->whereDate('payin_transactions.created_at',$date);
        }
        if($userId != ""){
            if (is_array($userId)){
                $query->whereIn('payin_transactions.userId',$userId);
            }else{
                $query->where('payin_transactions.userId',$userId);
            }           
        }
        if($search != ""){
            $query->where('payin_transactions.txnId', $search)->orWhere('payin_transactions.orderId', $search);
        }

        return $query->skip($start)->take($length)->orderBy('payin_transactions.id','desc');
    }

    public function getDailyTxn()
    {
        $status = ['SUCCESS','PENDING','PROCESSING'];
        $dailyTransactionAmounts = DB::table('user_transactions')
            ->select(
                DB::raw('DATE(created_at) as date'), // Extract the date part
                DB::raw('SUM(credit) as total_credit'),// Sum the amount for each date
                DB::raw('SUM(debit) as total_debit'), // Sum the amount for each date
            )
            ->whereIn('status',$status)
            ->groupBy('date') // Group by the date
            ->get();
        
        
        foreach ($dailyTransactionAmounts as $transaction) {
            $timestamp = strtotime($transaction->date); // Convert date to timestamp
            $transaction->date_timestamp = $timestamp;
        }

        return $dailyTransactionAmounts;
    }

    public function getPayinTotal()
    {
        return $totalAmount = DB::table('payin_transaction')->sum('amount');
    }

    public function getDmtTotal()
    {
        return $totalAmount = DB::table('dmt_transaction')->sum('amount');
    }

    

}
