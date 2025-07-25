<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentialComplexCategoryPivot extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'complex_id', 'linked_table_value' => 'id'],
        'ResidentialComplexCategory' => ['main_table_value' => 'category_id', 'linked_table_value' => 'id'],
    ];

    protected $fillable = ['complex_id', 'category_id'];
}
