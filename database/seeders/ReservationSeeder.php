<?php

namespace Database\Seeders;

use App\Core\Common\SeederCounterEnum;
use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Models\Apartment;
use App\Models\Interaction;
use App\Models\Manager;
use App\Models\Reservation;
use App\Models\User;
use App\Repositories\ReservationRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservationCollection = new Collection();
        $interactionCollection = new Collection();

        for ($i = 0; $i < SeederCounterEnum::ReservationCountSeed->value; $i++) {
            $modelReservation = new Reservation();
            $modelReservation->key = Str::uuid()->toString();
            $modelReservation->save();
            $reservationCollection->push($modelReservation);
        }

        for ($i = 0; $i < SeederCounterEnum::InteractionCountSeed->value; $i++) {
            if ($reservationCollection->isNotEmpty()) {
                $modelInteraction = new Interaction();
                $modelInteraction->manager_id = Manager::inRandomOrder()->first()->id;
                $modelInteraction->user_id = User::inRandomOrder()->limit(1)->first()->id;
                $modelInteraction->apartment_id = Apartment::inRandomOrder()->first()->id;
                $modelInteraction->key = Str::uuid()->toString();
                $modelInteraction->reservation_key = $reservationCollection->random()->key;
                $modelInteraction->save();
                $interactionCollection->push($modelInteraction);
            } else {
                Log::error("Коллекция Reservation пустая!");
            }
        }
    }
}
