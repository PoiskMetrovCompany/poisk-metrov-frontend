@if (isset($docs) && count($docs) > 0)
    <div class="docs base-container">
        <h2 class="real-estate title">Разрешительная документация застройщика</h2>
        <div class="docs container">
            @foreach ($docs as $item)
                <div class="docs item">
                    <img src="{{ Vite::asset('resources/assets/pdf.svg') }}">
                    <a target="_blank" href="{{ $item->doc_url }}">{{ $item->doc_name }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endif
