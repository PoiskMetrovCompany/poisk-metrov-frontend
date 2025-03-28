<?php

namespace App\Services;

use App\Core\Interfaces\Services\VisitedPagesServiceInterface;
use App\Models\VisitedPage;
use App\Repositories\VisitedPageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/**
 * Class VisitedPagesService.
 */
class VisitedPagesService extends AbstractService implements VisitedPagesServiceInterface
{
    public function __construct(protected VisitedPageRepositoryInterface $visitedPageRepository)
    {

    }
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
            $extraCodesInTable = $this->visitedPageRepository->findUniqueCode($userId, $pageCode, $codes);
            $codes->push(...$extraCodesInTable);
        }

        return $codes;
    }

    public static function getFromApp(): VisitedPagesService
    {
        return parent::getFromApp();
    }
}
