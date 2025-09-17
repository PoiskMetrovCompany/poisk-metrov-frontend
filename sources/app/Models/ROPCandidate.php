<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ROPCandidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "rop_candidates";

    /**
     * @var string[]
     */
    protected $fillable = [
        'key',
        'rop_key',
        'candidate_key',
    ];

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the candidate profile that owns this relationship
     */
    public function candidateProfile()
    {
        return $this->belongsTo(CandidateProfiles::class, 'candidate_key', 'key');
    }

    /**
     * Get the ROP account that owns this relationship
     */
    public function ropAccount()
    {
        return $this->belongsTo(Account::class, 'rop_key', 'key');
    }
}
