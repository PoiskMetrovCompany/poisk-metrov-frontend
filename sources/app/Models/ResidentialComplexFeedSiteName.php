<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentialComplexFeedSiteName extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_name',
        'site_name',
        'create_new',
        'pair_found'
    ];
}
