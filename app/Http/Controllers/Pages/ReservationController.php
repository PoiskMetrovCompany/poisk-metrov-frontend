<?php

namespace App\Http\Controllers\Pages;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Http\Resources\Interaction\InteractionCollection;
use App\Http\Resources\Interactions\InteractionResource;
use App\Http\Resources\Managers\ManagerCollection;
use App\Http\Resources\Reservations\ReservationResource;
use App\Http\Resources\UserResource;
use App\Providers\AppServiceProvider;
use App\Repositories\InteractionRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\UserRepository;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * @see AppServiceProvider::registerReservation()
 * @see ReservationService::getApartmentRelationship()
 * @see ReservationService::getRelationshipInteraction()
 * @see ReservationRepository::$model
 * @see ReservationRepository::list()
 * @see InteractionRepository::read()
 * @see UserRepositoryInterface::read()
 * @see ApartmentRepositoryInterface::read()
 * @see ApartmentRepositoryInterface::list()
 * @see ManagerRepositoryInterface::list()
 */
class ReservationController extends Controller
{
    protected ReservationService $reservationService;
    protected ReservationRepository $reservationRepository;
    protected InteractionRepository $interactionRepository;
    protected UserRepositoryInterface $userRepository;
    protected ApartmentRepositoryInterface $apartmentRepository;
    protected ManagerRepositoryInterface $managerRepository;

    public function __construct(
        ReservationService $reservationService,
        ReservationRepository $reservationRepository,
        InteractionRepository $interactionRepository,
        UserRepositoryInterface $userRepository,
        ApartmentRepositoryInterface $apartmentRepository,
        ManagerRepositoryInterface $managerRepository
    )
    {
        $this->reservationService = $reservationService;
        $this->reservationRepository = $reservationRepository;
        $this->interactionRepository = $interactionRepository;
        $this->userRepository = $userRepository;
        $this->apartmentRepository = $apartmentRepository;
        $this->managerRepository = $managerRepository;
    }

    /**
     * @param int $id
     * @return View
     */
    public function indexPage(int $id): View
    {
        $reservation = new ReservationResource($this->reservationRepository->findById($id));
        $interaction = new InteractionResource($this->interactionRepository->findByKey(['reservation_key' => $reservation->key]));
        $client = new UserResource($this->userRepository->findById($interaction->user_id));
        $apartment = new ApartmentResource($this->apartmentRepository->findById($interaction->apartment_id));
        $managerList = new ManagerCollection($this->managerRepository->list(['id' => $interaction->manager_id]));
        $managerList = $managerList->resource;
        $apartmentList = new ApartmentCollection($this->interactionRepository->list(['user_id' => $interaction->user_id]));
        $bookings = $apartmentList->resource;

        return View('reservation.index', compact(
            'reservation',
            'interaction',
            'client',
            'apartment',
            'managerList',
            'bookings'
        ));
    }
}
