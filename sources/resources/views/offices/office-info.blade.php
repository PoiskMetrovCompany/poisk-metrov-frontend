<div class="offices office-container">
    <div class="offices item">
        <div class="offices image-container">
            <img src="{{ Vite::asset($image) }}" class="offices image">
        </div>
    </div>
    <div class="offices item">
        <div class="offices contacts">
            <div class="icon map d20x20 orange"></div>
            {!! $address !!}
        </div>
        <div class="offices contacts">
            <div class="icon content-time d20x20 orange"></div>
            {{ $schedule }}
        </div>
        <div class="offices contacts">
            <div class="icon content-phone d20x20 orange"></div>
            {{ $phone }}
        </div>
    </div>
    <div class="common-button" id="{{ $buttonId }}">Записаться на встречу</div>
</div>
