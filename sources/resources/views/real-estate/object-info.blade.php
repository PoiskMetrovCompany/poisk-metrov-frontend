<div class="object-info base-container">
    <h2 class="real-estate title">Об объекте</h2>
    <div class="object-info container">
        @if (isset($buildingMaterials) && $buildingMaterials != '')
            <div class="object-info unit">
                <img src="{{ Vite::asset('resources/assets/estate-info/walls.svg') }}">
                <div class="object-info text">
                    <div>{{ $buildingMaterials }}</div>
                    <div>тип стен</div>
                </div>
            </div>
        @endif
        @if (isset($ceilingHeight) && $ceilingHeight != '')
            <div class="object-info unit">
                <img src="{{ Vite::asset('resources/assets/estate-info/ceilings.svg') }}">
                <div class="object-info text">
                    <div>{{ $ceilingHeight }} м</div>
                    <div>высота потолков</div>
                </div>
            </div>
        @endif
        @if (isset($elevator) && $elevator != '')
            <div class="object-info unit">
                <img src="{{ Vite::asset('resources/assets/estate-info/elevators.svg') }}">
                <div class="object-info text">
                    <div>{{ $elevator }}</div>
                    <div>лифт</div>
                </div>
            </div>
        @endif
        @if (isset($floorsTotal) && $floorsTotal != '')
            <div class="object-info unit">
                <img src="{{ Vite::asset('resources/assets/estate-info/floors.svg') }}">
                <div class="object-info text">
                    <div>{{ $floorsTotal }}</div>
                    <div>этажей</div>
                </div>
            </div>
        @endif
        @if (isset($parking) && $parking != '')
            @if ($parking != '' && $parking != 'NULL')
                <div class="object-info unit">
                    <img src="{{ Vite::asset('resources/assets/estate-info/parking.svg') }}">
                    <div class="object-info text">
                        <div>{{ $parking }}</div>
                        <div>стоянка</div>
                    </div>
                </div>
            @endif
        @endif
        @if (isset($corpuses) && $corpuses != 0)
            <div class="object-info unit">
                <img src="{{ Vite::asset('resources/assets/estate-info/corpuses.svg') }}">
                <div class="object-info text">
                    <div>{{ $corpuses }}</div>
                    <div>
                        @if ($corpuses > 4)
                            корпусов
                        @else
                            @if ($corpuses > 1)
                                корпуса
                            @else
                                корпус
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
