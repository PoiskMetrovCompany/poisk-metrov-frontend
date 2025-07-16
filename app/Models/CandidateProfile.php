<?php

namespace App\Models;

use App\Core\Common\CandidateProfileStatusesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;
    public const CANDIDATE_PROFILE_STATUS_DEFAULT = CandidateProfileStatusesEnum::NEW;

    protected $fillable = [
        'key',
        'message',
        'sender_id'
    ];
}
