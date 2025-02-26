<city-selection open="0">
    <dialog>
        <header>
            <div>
                Выберите город
            </div>
            <button>
                @include('icons.close')
            </button>
        </header>
        <section>
            <div type="hint">
                Текущий город
            </div>
            <div type="current">
                @include('icons.check')
                <span>{{ $cityName }}</span>
            </div>
        </section>
        <nav>
            <ul>
                @foreach ($cityService->getSortedCityNamesAndCodes() as $cityNameSorted => $cityCode)
                    <li>
                        <a href="/switch-city?new_city={{ $cityCode }}" current="{{ $selectedCity == $cityCode }}">
                            {{ $cityNameSorted }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </dialog>
</city-selection>
