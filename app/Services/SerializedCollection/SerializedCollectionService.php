<?php

namespace App\Services\SerializedCollection;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\SerializedCollectionServiceInterface;

final class SerializedCollectionService implements SerializedCollectionServiceInterface
{
    use ApartmentListSerializedTrait;

    public function __construct(
        protected InteractionRepositoryInterface $interactionRepository,
        protected ApartmentRepositoryInterface $apartmentRepository,
        protected ManagerRepositoryInterface $managerRepository,
        protected UserRepositoryInterface $userRepository,
        protected ReservationRepositoryInterface $reservationRepository,
        protected ComplexRepositoryInterface $complexRepository,
    )
    {
        $this->interactionRepository = $interactionRepository;
        $this->apartmentRepository = $apartmentRepository;
        $this->managerRepository = $managerRepository;
        $this->userRepository = $userRepository;
        $this->reservationRepository = $reservationRepository;
        $this->complexRepository = $complexRepository;
    }
}
