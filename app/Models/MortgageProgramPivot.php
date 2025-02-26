<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MortgageProgramPivot extends Model
{
    use HasFactory;

    protected $table = 'mortgage_program_pivot';

    protected $fillable = ['mortgage_id', 'program_id'];
}
