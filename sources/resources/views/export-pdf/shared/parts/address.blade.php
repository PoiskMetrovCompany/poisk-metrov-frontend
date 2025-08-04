<div class="title-page-office-container">
    <img src="{{ Vite::asset('resources/assets/place-orange.svg') }}" class="place-icon">
    <div class="title-page-office-container-subtitle">
        {{ $address }}
        @foreach ($offices as $office)
            <br>
            {{ $office }}
        @endforeach
    </div>
</div>
