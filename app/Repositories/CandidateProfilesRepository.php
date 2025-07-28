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
use Illuminate\Support\Facades\Log;

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

    public function getCandidateProfiles(?string $key, array $columnsToSelect)
    {
        $query = DB::table('candidate_profiles');
//            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
//            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key');

        if (!empty($key)) {
            $query->where('candidate_profiles.key', '=', $key);
        }

        $query->select(
            'candidate_profiles.*',
//            'vacancies.title as vacancy_name',
//            'marital_statuses.title as marital_status_name',
            DB::raw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.name')), '') AS family_partner_name"),
            DB::raw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.age')), '') AS family_partner_age"),
            DB::raw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.relation')), '') AS family_partner_relation")
        );


        $query->orderBy('candidate_profiles.updated_at', 'desc');

        $results = $query->get();
        Log::info($results);

        $processedResults = $results->map(function($item) {
            $item->serviceman = $item->serviceman ? 'Да' : 'Нет';
            $item->is_data_processing = $item->is_data_processing ? 'Да' : 'Нет';

            $item->adult_family_members_list = '';
            if (!empty($item->adult_family_members)) {
                $familyMembers = json_decode($item->adult_family_members, true);
                if (is_array($familyMembers) && !empty($familyMembers)) {
                    $names = array_column($familyMembers, 'name');
                    $item->adult_family_members_list = implode(', ', array_filter($names));
                }
            }

            // Обработка adult_children
            $item->adult_children_list = '';
            if (!empty($item->adult_children)) {
                $children = json_decode($item->adult_children, true);
                if (is_array($children) && !empty($children)) {
                    $names = array_column($children, 'name');
                    $item->adult_children_list = implode(', ', array_filter($names));
                }
            }

            unset($item->family_partner);
            unset($item->adult_family_members);
            unset($item->adult_children);

            return $item;
        });

        return $processedResults;
    }



    public function getType(): string
    {
        return $this->model::class;
    }
}
