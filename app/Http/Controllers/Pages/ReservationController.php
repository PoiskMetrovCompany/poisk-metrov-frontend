<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use App\Repositories\InteractionRepository;
use App\Repositories\ReservationRepository;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * @see AppServiceProvider::registerReservation()
 * @see ReservationService::getApartmentRelationship()
 * @see ReservationService::getRelationshipInteraction()
 * @see ReservationRepository::$model
 * @see ReservationRepository::list()
 * @see InteractionRepository::read()
 */
class ReservationController extends Controller
{
    protected ReservationService $reservationService;
    protected ReservationRepository $reservationRepository;
    protected InteractionRepository $interactionRepository;

    public function __construct(
        ReservationService $reservationService,
        ReservationRepository $reservationRepository,
        InteractionRepository $interactionRepository
    )
    {
        $this->reservationService = $reservationService;
        $this->reservationRepository = $reservationRepository;
        $this->interactionRepository = $interactionRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function indexPage(Request $request): View
    {
        return View('reservation.index');
    }
}
