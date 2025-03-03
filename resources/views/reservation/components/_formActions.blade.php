<div class="action-form-card">
    @include('reservation.components._section-booking', ['bookings' => $bookings])
    @include('reservation.components._section-accordion', ['accordions' => $accordions])

{{--    <section class="action-form-card__mortgage-solution"></section>--}}
{{--    <section class="action-form-card__mortgage-agreement"></section>--}}
</div>

