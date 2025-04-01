<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\RelationshipEntityRepositoryInterface;
use App\Repositories\Queries\BuildingSortQueryTrait;
use App\Repositories\Queries\RelationshipEntityQuery\ComplexAndApartmentFilterQueryTrait;
use App\Repositories\Queries\RelationshipEntityQuery\FindByEquivalentSampleQueryTrait;
use App\Repositories\Queries\RelationshipEntityQuery\ProcessingOfPlacementDataNoteTrait;
use App\Repositories\Queries\RelationshipEntityQuery\ResidentialComplexIsCityCodeQueryTrait;

final class RelationshipEntityRepository implements RelationshipEntityRepositoryInterface
{
    use ProcessingOfPlacementDataNoteTrait;
    use ComplexAndApartmentFilterQueryTrait;
    use ResidentialComplexIsCityCodeQueryTrait;
    use FindByEquivalentSampleQueryTrait;
    use BuildingSortQueryTrait;
}
