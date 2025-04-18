<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'ipAddress',               
        'created_by',
        'updated_by',
    ];

}
