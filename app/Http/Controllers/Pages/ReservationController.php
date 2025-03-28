<?php

namespace App\Http\Controllers\Pages;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\ReservationServiceInterface;
use App\Core\Interfaces\Services\SerializedCollectionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Http\Resources\Complexes\ComplexResource;
use App\Http\Resources\Interaction\InteractionCollection;
use App\Http\Resources\Interactions\InteractionResource;
use App\Http\Resources\Managers\ManagerCollection;
use App\Http\Resources\Managers\ManagerResource;
use App\Http\Resources\Reservations\ReservationResource;
use App\Http\Resources\UserResource;
use App\Models\Apartment;
use App\Models\Manager;
use App\Models\Reservation;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Repositories\ComplexRepository;
use App\Repositories\InteractionRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\UserRepository;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
 * @see ComplexRepositoryInterface::findById()
 */
class ReservationController extends Controller
{
    protected ReservationServiceInterface $reservationService;
    protected SerializedCollectionServiceInterface $collectionService;
    protected ReservationRepository $reservationRepository;
    protected InteractionRepository $interactionRepository;
    protected UserRepositoryInterface $userRepository;
    protected ApartmentRepositoryInterface $apartmentRepository;
    protected ManagerRepositoryInterface $managerRepository;
    protected ComplexRepositoryInterface $complexRepository;

    public function __construct(
        ReservationServiceInterface $reservationService,
        SerializedCollectionServiceInterface $collectionService,
        ReservationRepositoryInterface $reservationRepository,
        InteractionRepositoryInterface $interactionRepository,
        UserRepositoryInterface $userRepository,
        ApartmentRepositoryInterface $apartmentRepository,
        ManagerRepositoryInterface $managerRepository,
        ComplexRepositoryInterface $complexRepository
    )
    {
        $this->reservationService = $reservationService;
        $this->collectionService = $collectionService;
        $this->reservationRepository = $reservationRepository;
        $this->interactionRepository = $interactionRepository;
        $this->userRepository = $userRepository;
        $this->apartmentRepository = $apartmentRepository;
        $this->managerRepository = $managerRepository;
        $this->complexRepository = $complexRepository;
    }

    public function indexPage(int $id)
    {
        $reservation = (new ReservationResource($this->reservationRepository->findById($id)))->resolve();
        $interaction = (new InteractionResource($this->interactionRepository->findByKey(['reservation_key' => $reservation['key']])))->resolve();
        $client = (new UserResource($this->userRepository->findById($interaction['client']['id'])))->resolve();
        $apartment = (new ApartmentResource($this->apartmentRepository->findById($interaction['apartment']->id)))->resolve();
        $complex = key_exists('complex_id', $apartment) ? (new ComplexResource($this->complexRepository->findById($apartment['complex_id'])))->resolve() : null;
        $managerList = (new ManagerResource($this->managerRepository->findById($interaction['manager']->id)))->resolve();
        $apartmentList = $this->collectionService->apartmentListSerialized($client['id'], $apartment);

        return View('reservation.index', compact(
            'reservation',
            'interaction',
            'client',
            'apartment',
            'complex',
            'managerList',
            'apartmentList'
        ));
    }
}
