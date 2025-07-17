<?php

namespace App\Models\Log;

use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Facades\DB;

class FeedLoadingLog extends Model
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
        'admin_id',
        'found_feed',
        'loaded_feed',
        'action',
        'created_at',
        'updated_at',
    ];

    public function list()
    {

    }

    public function store(array $attributes)
    {

    }

    public function read(array $attributes)
    {

    }
    public function destroy(Model $entity)
    {

    }
}
