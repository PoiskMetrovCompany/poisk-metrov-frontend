<div id="names-filter-dropdown" class="custom-dropdown base-container names">
    <div class="names-dropdown buttons-container">
        @include('dropdown.names.category-button', ['buttonText' => 'Все'])
        @include('dropdown.names.category-button', ['buttonText' => 'Район'])
        @include('dropdown.names.category-button', ['buttonText' => 'Улица'])
        @if (count($searchData->stations->values))
            @include('dropdown.names.category-button', ['buttonText' => 'Метро'])
        @endif
        @include('dropdown.names.category-button', ['buttonText' => 'ЖК'])
        @include('dropdown.names.category-button', ['buttonText' => 'Застройщик'])
        @include('dropdown.names.close-button')
    </div>
    @isset($searchData)
        <div class="names-dropdown container" context="Все">
            @include('dropdown.names.list.district')
            @include('dropdown.names.list.street')
            @include('dropdown.names.list.metro')
            @include('dropdown.names.list.building')
            @include('dropdown.names.list.builder')
        </div>
        <div class="names-dropdown container" context="Район">
            @include('dropdown.names.list.district')
        </div>
        <div class="names-dropdown container" context="Улица">
            @include('dropdown.names.list.street')
        </div>
        <div class="names-dropdown container" context="Метро">
            @include('dropdown.names.list.metro')
        </div>
        <div class="names-dropdown container" context="ЖК">
            @include('dropdown.names.list.building')
        </div>
        <div class="names-dropdown container" context="Застройщик">
            @include('dropdown.names.list.builder')
        </div>
    @else
        @include('dropdown.names.nothing')
    @endisset
</div>
