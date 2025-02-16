<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'userId',
        'charge',               
        'gst',        
        'created_by',
        'updated_by',
    ];
}
