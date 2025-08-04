<div class="page">
    @include('export-pdf.shared.parts.header')
    @include('export-pdf.shared.parts.divider')
    <div class="apartment-title">{{ $apartment['apartment_type'] }}</div>
    <div class="apartment-details">
        @php
            $planImage = $fileService->getFileAsBase64($apartment['plan_URL']);
        @endphp
        @isset($planImage)
            <img src="{{ $apartment['plan_URL'] }}" class="apartment-plan">
        @endisset
        <div class="object-description">
            <div class="object-description-container">
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Срок сдачи',
                    'description' => "{$apartment['ready_quarter']} кв. {$apartment['built_year']}",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Корпус',
                    'description' => "{$apartment['building_section']}",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Отделка',
                    'description' => "{$apartment['renovation']}",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Этаж',
                    'description' => "{$apartment['floor']} из {$apartment['floors_total']}",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Номер квартиры',
                    'description' => "{$apartment['apartment_number']}",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Высота потолков',
                    'description' => "{$apartment['ceiling_height']}",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Общая площадь',
                    'description' => "{$apartment['area']} м²",
                ])
                @include('export-pdf.apartment.parts.description-item', [
                    'title' => 'Жилая площадь',
                    'description' => "{$apartment['living_space']} м²",
                ])
            </div>
            <div class="description-price">
                <div class="description-title">Цена</div>
                {{ $apartment['displayPrice'] }}
            </div>
        </div>
    </div>
    @php
        $floorPlan = $fileService->getFileAsBase64($apartment['floor_plan_url']);
        $preview = $fileService->getFileAsBase64($complex['previewImage']);
    @endphp
    <div @if (!isset($floorPlan)) class="apartment-images just-one" @else class="apartment-images" @endif>
        @isset($floorPlan)
            <img src="data:image/png;base64,{{ $floorPlan }}">
        @endisset
        @isset($preview)
            <img src="data:image/png;base64,{{ $preview }}">
        @endisset
    </div>
    @include('export-pdf.shared.parts.about')
</div>
