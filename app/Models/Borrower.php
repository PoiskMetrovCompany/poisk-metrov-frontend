<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booked_order_id',
        'fio',
        'birth_date',
        'citizenship',
        'education',
        'marital_status',
        'income',
        'is_primary',
    ];
}
