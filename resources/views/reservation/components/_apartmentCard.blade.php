<nav class="apartment-grid">
    @foreach($menuApartmentTitle as $item)
        <div class="apartment-col @if($item === 'Жилой комплекс') apartment-col__15 @else apartment-col__10 @endif">
            {{ $item }}
        </div>
    @endforeach

</nav>
@for($i=0; $countApartments > $i; $i++)
    @if ($i == 0)
        <section class="apartment-grid apartment-grid__wrap apartment-card apartment-top__round">
    @elseif (($i + 1)== $countApartments)
        <section class="apartment-grid apartment-grid__wrap apartment-card apartment-bottom__round">
    @else
        <section class="apartment-grid apartment-grid__wrap apartment-card">
    @endif
        <div class="apartment-col apartment-col__15">{{ $apartmentsList[$i]['name'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['developer'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['order_id'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['data_tz'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['count_rooms'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['price'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['per_m2'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['uniqueness'] }}</div>
        <div class="apartment-col apartment-col__10">
            <a href="{{ $apartmentsList[$i]['details']['href'] }}" class="apartment-card__href">
                Подробнее
            </a>
        </div>
    </section>
@endfor
