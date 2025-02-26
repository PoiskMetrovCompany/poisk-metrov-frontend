@php
    $minPrice = $priceFormattingService->priceToText($apartmentRepository->getCheapestApartmentPrice(), ' ', '', 1);
@endphp

<div class="favorites registered {{ $subclass ?? '' }}">
    <a href="/catalogue" class="favorites card">
        <div class="favorites select-plan base-container">
            <div class="favorites select-plan plus">+</div>
            <div class="favorites select-plan text-container">
                <div class="favorites item-title">Выбрать планировку</div>
                <div class="favorites select-plan subtitle">от {{ $minPrice }} ₽</div>
            </div>
        </div>
    </a>
    <div class="favorites card">
        <div class="favorites item-title">
            Оставьте заявку и наш брокер поможет вам с выбором
        </div>
        <div class="favorites card buttons-grid">
            <a href="javascript:void(0)" id={{ $buttonId ?? 'favorites-leave-request' }} class="common-button">
                Оставить заявку
            </a>
        </div>
    </div>
    @include('favorites.favorites-card')
</div>
