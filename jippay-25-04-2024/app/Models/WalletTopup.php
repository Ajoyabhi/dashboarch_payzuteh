<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WalletTopup extends Model
{
    use HasFactory;
    protected $fillable = [
        'userId',
        'amount',
        'charge',
        'gst',
        'totalAmount',               
        'utr',
        'requestedBy',
        'requestedRemark',
        'approvedBy',               
        'approvedRemark',
        'status',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function getWalletDataAjax($start,$length,$date="",$search="",$userId=""){

        $selectedColumns = ['amount','charge','gst','totalAmount','utr','created_at','status'];
        $query = WalletTopup::select($selectedColumns)->where('status',"APPROVED");
        if($date != ""){
            $query->whereDate('created_at',$date);
        }
        if($userId != ""){
            $query->where('userId',$userId);
        }
        // if($search != ""){
        //     $query->where('txnId', $search)->orWhere('orderId', $search);
        // }
        return $query->skip($start)->take($length)->orderBy('id','desc');
    }


    public function getWalletData(){
        $today = Carbon::today();
        $selectedColumns = ['wallet_topups.*','users.name'];
        return $query = WalletTopup::select($selectedColumns)->join('users', '.userId', '=', 'users.id')->whereDate('wallet_topups.created_at',$today)->where('wallet_topups.status',"PENDING")->orderBy('wallet_topups.id','desc')->get();     
    }

    public function getWalletDataSearch($user,$status,$date)
    {
        $selectedColumns = ['wallet_topups.*','users.name'];
        $query = WalletTopup::select($selectedColumns)->join('users', '.userId', '=', 'users.id');
        if($user != "ALL"){
            $query->where('wallet_topups.userId',$user);
        }
        if($status != "ALL"){
            $query->where('wallet_topups.status',$status);
        }
        if($date != ""){
            $query->whereDate('wallet_topups.created_at',$date);
        }
        return $query = $query->orderBy('wallet_topups.id','desc')->get();
    }

}
