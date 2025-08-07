<a href="/articles/{{ $id }}">
    <div class="news-card card {{ $fixed }}">
        <div class="news-card banner">
            <img src="{{ $banner }}">
        </div>
        <div class="news-card content container">
            <div class="news-card content header">
                <div class="news-card content header date">{{ $textService->formatDate($date) }}</div>
                {{ $title }}
            </div>
            <div class="news-card content text">
                {!! $content !!}
            </div>
        </div>
    </div>
</a>
