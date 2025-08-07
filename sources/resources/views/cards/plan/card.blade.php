{{-- @php --}}
{{-- $formattedPrice = $priceFormattingService->priceToText($price); --}}
{{-- @endphp --}}
<div class="plan-card container" offerId="{{ $offerId }}" apartment_type="{{ $type }}"
    area="{{ $area }} м²" price="{{ $formattedPrice }}">
    <div class="plan-card small-card">
        <div class="plan-card header">
            <a target="_self" href="/{{ $offerId }}">
                {{ $name }}
            </a>
            <div class="plan-card icons">
                <div tabindex="-1" class="plan-card card-button {!! $isFavoriteApartment ? 'orange' : '' !!}">
                    <div class="icon action-like d24x24 black"></div>
                    @include('common.hint', ['text' => 'Добавить в избранное'])
                </div>
                @include('common.share-page-button', [
                    'id' => "share-apartment-button-$offerId",
                ])
            </div>
        </div>
        @include('cards.plan.image')
        <div class="plan-card short-description">
            <div class="plan-card title">{{ $formattedPrice }}</div>
            <div class="plan-card line-desc">
                @include('cards.plan.description-line', [
                    'text' => "$type",
                    'valueToCheck' => $type,
                ])
                @include('cards.plan.dot-divider')
                @include('cards.plan.description-line', [
                    'text' => "$area м²",
                    'valueToCheck' => $area,
                ])
                @include('cards.plan.dot-divider')
                @include('cards.plan.description-line', [
                    'text' => "$floor этаж",
                    'valueToCheck' => $floor,
                ])
            </div>
            @isset($hidePriceChangeButtonText)
                @include('common.price-change', [
                    'idModifier' => $offerId,
                    'hidePriceChangeButtonText' => $hidePriceChangeButtonText,
                ])
            @else
                @include('common.price-change', [
                    'idModifier' => $offerId,
                ])
            @endisset
            <div class="plan-card details">
                @isset($quarter)
                    <div class="plan-card description">Срок сдачи: {{ $quarter }} квартал {{ $builtYear }}</div>
                @else
                    @isset($builtYear)
                        <div class="plan-card description">Срок сдачи: {{ $builtYear }}</div>
                    @endisset
                @endisset
                @include('cards.plan.description-line', [
                    'text' => "Тип дома: $material",
                    'valueToCheck' => $material,
                ])
                @include('cards.plan.description-line', [
                    'text' => "Отделка: $finishing",
                    'valueToCheck' => $finishing,
                ])
            </div>
        </div>
        <a target="_self" href="/{{ $offerId }}" class="common-button bordered">
            Подробнее
        </a>
    </div>
</div>
