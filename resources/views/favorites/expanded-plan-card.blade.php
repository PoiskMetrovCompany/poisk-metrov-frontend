<div class="expanded-plan-card container" offerId="{{ $offer_id }}" apartment_type="{{ $apartment_type }}"
    area="{{ $area }} м²" price="{{ $displayPrice }}">
    <div class="expanded-plan-card small-card">
        <div class="expanded-plan-card header">
            <div class="expanded-plan-card icons">
                <div tabindex="-1" class="plan-card card-button {!! $isFavoriteApartment ? 'orange' : '' !!}">
                    <div class="icon action-like d24x24 black"></div>
                    @include('common.hint', ['text' => 'Добавить в избранное'])
                </div>
                @include('common.share-page-button', [
                    'id' => "share-apartment-button-$offer_id",
                ])
            </div>
        </div>
        <a class="expanded-plan-card img-container" href="/{{ $offer_id }}">
            <img src="{{ $plan_URL }}" class="expanded-plan-card img">
        </a>
        @include('favorites.deleted', ['title' => 'Вы удалили эту квартиру из Избранного'])
    </div>
    <div @auth class="expanded-plan-card short-description" @endauth
        @guest class="expanded-plan-card short-description blur" @endguest>
        <a class="expanded-plan-card title" target="_self" href="/{{ $offer_id }}"> {{ $name }} </a>
        <div class="expanded-plan-card title">{{ $displayPrice }}</div>
        <div class="expanded-plan-card details">
            @include('favorites.expanded-plan-card.line', [
                'title' => 'Комнатность',
                'value' => $apartment_type,
                'checkValue' => $apartment_type,
            ])
            @include('favorites.expanded-plan-card.line', [
                'title' => 'Площадь',
                'value' => "$area м²",
                'checkValue' => $area,
            ])
            @include('favorites.expanded-plan-card.line', [
                'title' => 'Отделка',
                'value' => "$renovation",
                'checkValue' => $renovation,
            ])
        </div>
        <div class="expanded-plan-card title">Расположение</div>
        <div class="expanded-plan-card details">
            @include('favorites.expanded-plan-card.line', [
                'title' => 'Этаж',
                'value' => "$floor этаж",
                'checkValue' => $floor,
            ])
            @include('favorites.expanded-plan-card.line', [
                'title' => 'Корпус',
                'value' => "$building_section",
                'checkValue' => $building_section,
            ])
            @include('favorites.expanded-plan-card.line', [
                'title' => 'Номер квартиры',
                'value' => "$apartment_number",
                'checkValue' => $apartment_number,
            ])
        </div>
    </div>
    <a target="_self" href="/{{ $offer_id }}" class="common-button bordered">
        Подробнее
    </a>
</div>
