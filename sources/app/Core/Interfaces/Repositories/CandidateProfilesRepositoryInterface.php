<?php

namespace App\Core\Interfaces\Repositories;

use Illuminate\Database\Query\Builder;

/**
 * @template TRepository
 */
interface CandidateProfilesRepositoryInterface extends BaseRepositoryInterface
{
    public function getCandidateProfiles(?string $key, array $columnsToSelect);
}
