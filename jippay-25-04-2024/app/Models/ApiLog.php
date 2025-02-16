<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;
    protected $table = 'api_logs';

    protected $fillable = [
        'txnId',
        'request',               
        'response',
        'service',
        'service_api',
    ];
}
