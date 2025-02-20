<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizationCall extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'pincode', 'call_id'];
}
