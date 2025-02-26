<fieldset class="complex-address" tabindex="-1">
    <legend class="address-title">Расположение</legend>
    <div class="address-container">
        <div class="address-item">
            @php
                $travelType = 'пешком';

                if ($complex['metro_type'] == 'transport') {
                    $travelType = 'на машине';
                }
            @endphp
            {{ $complex['metro_time'] }} минут {{ $travelType }}
            <br>
            до станции «{{ $complex['metro_station'] }}»
        </div>
        <div class="address-item">
            {{ $complex['location']->district }} район
            <br>
            {{ $complex['address'] }}
        </div>
    </div>
</fieldset>
