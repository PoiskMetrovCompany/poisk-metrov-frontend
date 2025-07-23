<?php

namespace App\Repositories;

use App\Core\Common\CandidateProfileExportColumnsConst;
use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Models\CandidateProfiles;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;
use Illuminate\Support\Facades\DB;

final class CandidateProfilesRepository implements CandidateProfilesRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    public function __construct(
        protected CandidateProfiles $model,
    )
    {

    }

    public function getCandidateProfiles(?string $key, mixed $realColumns)
    {
        $query = DB::table('candidate_profiles')
            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key');

        if (!empty($key)) {
            $query->where('candidate_profiles.key', '=', $key);
        }

        $query->select($realColumns);
        $query->selectRaw("
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.name')), '') AS family_partner,
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.age')), '') AS family_partner_age,
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.relation')), '') AS family_partner_relation,

            COALESCE(
                GROUP_CONCAT(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.adult_family_members, '$[*].name')) SEPARATOR ', '),
                ''
            ) AS adult_family_members_list,

            COALESCE(
                GROUP_CONCAT(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.adult_children, '$[*].name')) SEPARATOR ', '),
                ''
            ) AS adult_children_list
        ");
        $query->groupBy(CandidateProfileExportColumnsConst::GROUP_BY_COLUMNS);
        $query->orderBy('candidate_profiles.updated_at', 'desc');
        return $query->get();
    }



    public function getType(): string
    {
        return $this->model::class;
    }
}
