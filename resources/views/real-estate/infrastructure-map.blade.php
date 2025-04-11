@php
    if (!isset($infrastructure)) {
        $infrastructure = '{}';
    }
@endphp

<div class="base-container">
    <script>
        let buildingCoordinates = [{{ $longitude }}, {{ $latitude }}];
        let zoom = 17;
        let infrastructure = {!! $infrastructure !!};
        let previewLink = "/{{ $previewImage }}";
    </script>
    @vite('resources/js/infrastructure/loader.js')
    <div class="location header-with-button">
        <h2 class="title">Расположение комплекса</h2>
        <div id="open-infrastructure-menu" class="location infrastructure-button"
            @if ($infrastructure == '{}') style="opacity: 0.5; pointer-events: none; display: none" @endif>
            >
            <div class="icon filters-button black"></div>
        </div>
    </div>
    <div class="location content">
        <div id="map" class="location map-frame"></div>
        <div id="location-menu" class="location menu container"
            @if ($infrastructure == '{}') style="opacity: 0.5; pointer-events: none; cursor: not-allowed" @endif>
            <div class="infrastructure menu title">Инфраструктура</div>
            <div class="infrastructure menu items">
                <div id="infra-metro" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="metro" class="infrastructure menu icon-container">
                            <div class="icon content-metro grey4 d24x24"></div>
                        </div>
                        <div>Метро</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
                <div id="infra-schools" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="school" class="infrastructure menu icon-container">
                            <div class="icon content-book grey4 d24x24"></div>
                        </div>
                        <div>Школы</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
                <div id="infra-children" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="kids" class="infrastructure menu icon-container">
                            <div class="icon content-children grey4 d24x24"></div>
                        </div>
                        <div>Детские сады</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
                <div id="infra-parks" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="park" class="infrastructure menu icon-container">
                            <div class="icon content-tree grey4 d24x24"></div>
                        </div>
                        <div>Парки</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
                <div id="infra-shops" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="shop" class="infrastructure menu icon-container">
                            <div class="icon content-shop grey4 d24x24"></div>
                        </div>
                        <div>Магазины</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
                <div id="infra-sport" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="sport" class="infrastructure menu icon-container">
                            <div class="icon content-dumbbell grey4 d24x24"></div>
                        </div>
                        <div>Спорт</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
                <div id="infra-health" class="infrastructure menu item">
                    <div class="infrastructure menu text-with-icon">
                        <div iconType="health" class="infrastructure menu icon-container">
                            <div class="icon content-hospital grey4 d24x24"></div>
                        </div>
                        <div>Аптеки</div>
                    </div>
                    <div class="icon check orange d24x24"></div>
                </div>
            </div>
            <div id="infra-select-all" class="location menu select-all">Выбрать все</div>
        </div>
    </div>
</div>
@include('menus.infrastructure')
