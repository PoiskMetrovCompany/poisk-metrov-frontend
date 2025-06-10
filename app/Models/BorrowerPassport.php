<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerPassport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'borrower_id',
        'number',
        'issue_date',
        'code',
        'issued_by',
        'place_of_birth',
        'registration_address_ru',
    ];
}
