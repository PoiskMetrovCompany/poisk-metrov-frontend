<?php

namespace App\Http\Controllers\Api\V1\Maps;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use Illuminate\Http\Request;

class GetMapController extends AbstractOperations
{
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
    )
    {

    }

    public function __invoke(Request $request)
    {

    }

    public function getEntityClass(): string
    {
        return 'AbstractMapResidentialComplex';
    }

    public function getResourceClass(): string
    {
        return 'AbstractMapResidentialComplexResource';
    }
}
