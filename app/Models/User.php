<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // const ROLE_ADMIN = 'admin';
    // const ROLE_USER = 'payin-payout';
    // const ROLE_STAFF = 'staff';
    // const ROLE_AGENT = 'agent';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'password',
        'settlement',
        'wallet',
        'lien',
        'rolling_reserve',
        'user_key',
        'user_token',
        'mobile',
        'email',
        'company_name',
        'aadhaar_card',
        'pancard',
        'address',
        'city',
        'state',
        'pincode',
        'status',
        'api_status',
        'tecnical_issue',
        'iserveu',
        'vouch',
        'bank_deactive',
        'user_type',
        'agent_id',
        'payout_callback',
        'payin_callback',
        'created_by',
        'updated_by',
        'gst_no',
        'pin',
        'business_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
   
    public function transactions()
    {
        return $this->hasMany(UserTransaction::class);
    }

    public function transactionsWallet()
    {
        return $this->hasMany(WalletTopup::class);
    }

    public function addFund($userId,$amount)
    {
        // User::where('id', $userId)
        // ->update(['settlement' => \DB::raw("settlement + $amount")]);
        User::where('id', $userId)
        ->update(['wallet' => \DB::raw("wallet + $amount")]);
    }
    
    public function addSettlementFund($userId,$amount)
    {
        User::where('id', $userId)
        ->update(['settlement' => \DB::raw("settlement + $amount")]);
    }

    public function deductFund($userId,$amount)
    {
        User::where('id', $userId)
        ->update(['settlement' => \DB::raw("settlement - $amount")]);
    }
}
