<?php

namespace App\Core\Interfaces\Repositories;

use Illuminate\Database\Query\Builder;

/**
 * @template TRepository
 */
interface CandidateProfilesRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string|null $key
     * @param mixed $realColumns
     */
    public function getCandidateProfiles(?string $key, mixed $realColumns);
}
