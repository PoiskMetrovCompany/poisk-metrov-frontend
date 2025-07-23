<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Facades\DB;

class UpdateCandidateProfileLog extends Model
{
    /**
     * @var string
     */
    protected $connection = 'pm-log';

    /**
     * @var string
     */
    protected $collection = 'update_candidate_profile_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'candidate_profiles_key',
        'action',
        'attributes',
        'created_at',
        'updated_at',
    ];

    public function store(array $attributes)
    {

    }
}
