<div class="action-form-card">
    @include('reservation.components._section-booking', ['bookings' => $bookings])
    @include('reservation.components._section-accordion', ['accordions' => $accordions])
    @include('reservation.components._section-mortgage-solution', [])
    @include('reservation.components._section-mortgage-agreement', [])
</div>

