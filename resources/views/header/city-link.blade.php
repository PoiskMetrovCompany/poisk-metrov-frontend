<a class="{!! $selectedCity == $forCity ? 'header city-flex selected' : 'header city-flex' !!}" href="/switch-city?new_city={{ $forCity }}">
    <div class="icon place"></div>
    <div>{{ $cityName }}</div>
</a>
