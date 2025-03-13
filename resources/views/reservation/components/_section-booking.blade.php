<section class="action-form-card__form-booking">
    <div class="action-form-card__form-title">
        <p>Детали заявки</p>
    </div>
    @foreach($bookings as $item => $key)
        <div class="action-form-card__form-container">
            <div class="action-form-card__title"><p>{{ $key['title'] }}</p></div>
            <div class="action-form-card__description"><p>{{ $key['description'] }}</p></div>
        </div>
    @endforeach
    @include('reservation.components._bottomActions')
</section>
