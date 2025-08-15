<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Facades\DB;

class Notification extends Model
{
    /**
     * @var string
     */
    protected $connection = 'pm-log';

    /**
     * @var string
     */
    protected $collection = 'notification';

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
