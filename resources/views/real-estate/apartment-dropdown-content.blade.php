@for ($i = 0; $i < count($apartments); $i++)
    @isset($apartmentSpecifics[$i])
        <div class="plans-filter apartment-dropdown header">
            <div>
                <div>
                    {{ $apartmentSpecifics[$i]['fullName'] }}
                </div>
                <div>
                    от {{ $apartmentSpecifics[$i]['minPriceCatalogue'] }}
                </div>
            </div>
            <div>
                {{ $apartmentSpecifics[$i]['count'] }} квартир
            </div>
            <div>
                от {{ $apartmentSpecifics[$i]['minSquare'] }} м²
            </div>
            <div>
                от {{ $apartmentSpecifics[$i]['minPriceCatalogue'] }}
            </div>
            <div class="icon arrow-tailless orange"></div>
            {{-- For mobile --}}
        </div>
        <div id="apartment-dropdown-{{ $i }}" class="plans-filter apartment-dropdown container">
            <div class="plans-filter apartment-dropdown buttons-grid" style="display: none">
                <div id="apartment-dropdown-price-sort-{{ $i }}" class="plans-filter apartment-dropdown buttons">
                    <div class="plans-filter apartment-dropdown button" roomCount="{{ $apartmentSpecifics[$i]['roomCount'] }}"
                        apartmentType="{{ $apartmentSpecifics[$i]['apartmentType'] }}">
                        Сначала дороже
                    </div>
                    <div class="plans-filter apartment-dropdown button active"
                        roomCount="{{ $apartmentSpecifics[$i]['roomCount'] }}"
                        apartmentType="{{ $apartmentSpecifics[$i]['apartmentType'] }}">
                        Сначала дешевле
                    </div>
                </div>
                <div id="apartment-dropdown-area-sort-{{ $i }}" class="plans-filter apartment-dropdown buttons">
                    <div class="plans-filter apartment-dropdown button"
                        roomCount="{{ $apartmentSpecifics[$i]['roomCount'] }}"
                        apartmentType="{{ $apartmentSpecifics[$i]['apartmentType'] }}">
                        По росту площади
                    </div>
                    <div class="plans-filter apartment-dropdown button active"
                        roomCount="{{ $apartmentSpecifics[$i]['roomCount'] }}"
                        apartmentType="{{ $apartmentSpecifics[$i]['apartmentType'] }}">
                        По убыванию площади
                    </div>
                </div>
            </div>
            @include('real-estate.plan-pages')
        </div>
    @endisset
@endfor
