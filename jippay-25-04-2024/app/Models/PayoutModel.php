<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PayoutModel extends Model
{
    use HasFactory;
    protected $table = 'payout_transactions';

    protected $fillable = [
        'userId',
        'txnId',               
        'orderId',
        'contactId',
        'amount',
        'charge',   
        'gst',
        'mode',            
        'totalAmount',
        'beneName',
        'beneBank',
        'beneAccount',
        'beneIfsc',
        'utr',
        'api',
        'remark',
        'status',
        'IpAddress',
    ];

    public function updatePayoutData($data,$txnId)
    {
        DB::table('payout_transactions')
        ->where('txnId',$txnId)
        ->update($data);
    }

    public function getWalletDataAjax($start,$length,$date="",$search="",$userId=""){

        $selectedColumns = ['payout_transactions.orderId','payout_transactions.txnId','payout_transactions.amount','payout_transactions.charge','payout_transactions.gst','payout_transactions.totalAmount','payout_transactions.beneName','payout_transactions.beneAccount','payout_transactions.beneIfsc','payout_transactions.utr','payout_transactions.status','payout_transactions.created_at','users.name'];
        $query = PayoutModel::select($selectedColumns)->join('users', 'payout_transactions.userId', '=', 'users.id');
        if($date != ""){
            $query->whereDate('payout_transactions.created_at',$date);
        }
        if($userId != ""){
            if (is_array($userId)){
                $query->whereIn('payout_transactions.userId',$userId);
            }else{
                $query->where('payout_transactions.userId',$userId);
            }           
        }
        if($search != ""){
            $query->where('payout_transactions.txnId', $search)->orWhere('payout_transactions.orderId', $search);
        }

        return $query->skip($start)->take($length)->orderBy('payout_transactions.id','desc');
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

    public function updatePayoutDataByOrderId($data,$orderId)
    {
        DB::table('payout_transactions')
        ->where('orderId',$orderId)
        ->update($data);
    }

}
