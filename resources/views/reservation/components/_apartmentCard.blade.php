<div class="full-row">
    @for($i=0; $countApartments > $i; $i++)
        @if ($i == 0)
            <div class="apartment-card apartment-top__round">
        @elseif (($i + 1)== $countApartments)
            <div class="apartment-card apartment-bottom__round">
        @else
            <div class="apartment-card">
        @endif
            <div class="apartment-card__grid">
                <div class="apartment-card__text">{{ $apartmentsList[$i]['name'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['developer'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['order_id'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['data_tz'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['count_rooms'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['price'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['per_m2'] }}</div>
                <div class="apartment-card__text">{{ $apartmentsList[$i]['uniqueness'] }}</div>
                <div class="apartment-card__text">
                    <a href="{{ $apartmentsList[$i]['details']['href'] }}" class="apartment-card__href">
                        Подробнее
                    </a>
                </div>
            </div>
        </div>
    @endfor
</div>
