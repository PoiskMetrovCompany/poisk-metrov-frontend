<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResidentialComplexCategory extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplexCategoryPivot' => ['main_table_value' => 'category_id', 'linked_table_value' => 'id'],
    ];

    protected $fillable = ['category_name'];

    public function residentialComplexes(): BelongsToMany
    {
        return $this->belongsToMany(ResidentialComplex::class, 'residential_complex_category_pivots', 'category_id', 'complex_id');
    }
}
