<div class="favorites menu">
    <a class="common-button {{ $onlyBuildings ? 'white1' : '' }}" id="plans">
        <div class="icon content-plan d28x28 {{ $onlyBuildings ? 'black' : 'white' }}"></div>
        Планировки
        <div id="favorite-apartment-counter" type="favCategoryCounter">
            {{ $plansCount }}
        </div>
    </a>
    <a class="common-button {{ !$onlyBuildings ? 'white1' : '' }}" id="complexes">
        <div class="icon buildings d28x28 {{ !$onlyBuildings ? 'black' : 'white' }}"></div>
        Жилые комплексы
        <div id="favorite-building-counter" type="favCategoryCounter">
            {{ $buildingsCount }}
        </div>
    </a>
</div>
