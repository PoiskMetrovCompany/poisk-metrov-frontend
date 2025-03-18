<?php

namespace App\Http\Controllers\Pages;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
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
    protected ReservationService $reservationService;
    protected ReservationRepository $reservationRepository;
    protected InteractionRepository $interactionRepository;
    protected UserRepositoryInterface $userRepository;
    protected ApartmentRepositoryInterface $apartmentRepository;
    protected ManagerRepositoryInterface $managerRepository;
    protected ComplexRepositoryInterface $complexRepository;

    public function __construct(
        ReservationService $reservationService,
        ReservationRepository $reservationRepository,
        InteractionRepository $interactionRepository,
        UserRepositoryInterface $userRepository,
        ApartmentRepositoryInterface $apartmentRepository,
        ManagerRepositoryInterface $managerRepository,
        ComplexRepositoryInterface $complexRepository
    )
    {
        $this->reservationService = $reservationService;
        $this->reservationRepository = $reservationRepository;
        $this->interactionRepository = $interactionRepository;
        $this->userRepository = $userRepository;
        $this->apartmentRepository = $apartmentRepository;
        $this->managerRepository = $managerRepository;
        $this->complexRepository = $complexRepository;
    }

    private function apartmentListSerialized(int $client_id, mixed $apartmentItem): array
    {
        // TODO: Тут какая то хрень с ресурсом ИСПРАВИТЬ! $apartmentList = $this->interactionRepository->list(['user_id' => $interaction['client']['id']]);
        return collect($this->interactionRepository->list(['user_id' => $client_id]))
            ->map(function ($apartmentItem) {
                $apartment = Apartment::find($apartmentItem['apartment_id']);
                return [
                    'id' => $apartmentItem['id'],
                    'key' => $apartmentItem['key'],
                    'apartment' => new ApartmentResource($apartment),
                    'manager' => new ManagerResource($this->managerRepository->findById($apartmentItem['manager_id'])),
                    'client' => new UserResource($this->userRepository->findById($apartmentItem['user_id'])),
                    'reservation' => new ReservationResource($this->reservationRepository->findByKey(['key' => $apartmentItem['reservation_key']])->first()),
                    'complexes' => new ComplexResource($this->complexRepository->findById($apartment->complex_id)),
                    'created_at' => $apartmentItem['created_at'],
                    'updated_at' => $apartmentItem['updated_at'],
                ];
            })
            ->all();
    }

    public function indexPage(int $id)
    {
        $reservation = (new ReservationResource($this->reservationRepository->findById($id)))->resolve();
        $interaction = (new InteractionResource($this->interactionRepository->findByKey(['reservation_key' => $reservation['key']])))->resolve();
        $client = (new UserResource($this->userRepository->findById($interaction['client']['id'])))->resolve();
        $apartment = (new ApartmentResource($this->apartmentRepository->findById($interaction['apartment']->id)))->resolve();
        $complex = key_exists('complex_id', $apartment) ? (new ComplexResource($this->complexRepository->findById($apartment['complex_id'])))->resolve() : null;
        $managerList = (new ManagerResource($this->managerRepository->findById($interaction['manager']->id)))->resolve();
        $apartmentList = $this->apartmentListSerialized($client['id'], $apartment);

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
