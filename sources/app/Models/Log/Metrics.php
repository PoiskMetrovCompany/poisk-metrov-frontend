<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metrics extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = 'pm-log';

    /**
     * @var string
     */
    protected $collection = 'metrics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'entity',
        'message',
        'created_at',
        'updated_at',
    ];
    
}
