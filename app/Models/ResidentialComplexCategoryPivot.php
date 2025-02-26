<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentialComplexCategoryPivot extends Model
{
    use HasFactory;

    protected $fillable = ['complex_id', 'category_id'];
}
