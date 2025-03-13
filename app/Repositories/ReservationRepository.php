<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Models\Reservation;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\ReadQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class ReservationRepository implements ReservationRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use ReadQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    protected Model $model;

    public function __construct() {
        $this->model = new Reservation();
    }
}
