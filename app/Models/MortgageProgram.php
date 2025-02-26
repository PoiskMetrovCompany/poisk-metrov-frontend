<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MortgageProgram extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function mortgages(): BelongsToMany
    {
        return $this->belongsToMany(Mortgage::class, 'mortgage_program_pivot', 'program_id', 'mortgage_id');
    }
}
