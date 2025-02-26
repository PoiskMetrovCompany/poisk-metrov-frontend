<?php

namespace App\Services;

use App\Models\VisitedPage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/**
 * Class VisitedPagesService.
 */
class VisitedPagesService extends AbstractService
{
    public function getVisitedApartments(): Collection
    {
        return $this->getVisitedPagesOfType('plan', 'lastVisitedApartments');
    }

    public function getVisitedBuildings(): Collection
    {
        return $this->getVisitedPagesOfType('real-estate', 'lastVisitedBuildings');
    }

    public function getVisitedPagesOfType(string $pageCode, string $cookieName): Collection
    {
        $codes = new Collection();
        $userId = Auth::id();
        $visitedPagesCookie = Cookie::get($cookieName);

        if (is_string($visitedPagesCookie)) {
            $codes->push(...explode(',', $visitedPagesCookie));
        } else if (is_array($visitedPagesCookie)) {
            $codes->push(...$visitedPagesCookie);
        }

        if ($userId != null) {
            $extraCodesInTable = VisitedPage::where('user_id', $userId)->where('page', $pageCode)->whereNotIn('code', $codes->toArray())->get()->pluck('code');
            $codes->push(...$extraCodesInTable);
        }

        return $codes;
    }

    public static function getFromApp(): VisitedPagesService
    {
        return parent::getFromApp();
    }
}
