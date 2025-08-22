<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;

/**
 * @template TRepository
 */
interface CandidateProfilesRepositoryInterface extends
    BaseRepositoryInterface,
    FindQueryBuilderInterface
{
    public function getCandidateProfiles(?string $key, array $columnsToSelect);
}
