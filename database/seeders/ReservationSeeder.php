<?php

namespace Database\Seeders;

use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Repositories\ReservationRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use SeederCounterEnum;

class ReservationSeeder extends Seeder
{
    protected ReservationRepositoryInterface $reservationRepository;
    protected InteractionRepositoryInterface $interactionRepository;
    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        InteractionRepositoryInterface $interactionRepository
    )
    {
        $this->reservationRepository = $reservationRepository;
        $this->interactionRepository = $interactionRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservationCollection = new Collection();
        for ($i = 0; $i < SeederCounterEnum::ReservationCountSeed->value; $i++) {
            $reservationCollection->add(
                $this->reservationRepository->store(['key' => Str::uuid()->toString()])
            );
        }

        $interactionCollection = new Collection();
        for ($i = 0; $i < SeederCounterEnum::InteractionCountSeed->value; $i++) {
            $reservationCollection->add(
                $this->reservationRepository->store([
                    'manager_id' => 1,
                    'user_id' => 2,
                    'apartment_id' => 1,
                    'key' => Str::uuid()->toString(),
                    'reservation_key' => $reservationCollection->random()->reservation_key,
                ])
            );
        }
    }
}
