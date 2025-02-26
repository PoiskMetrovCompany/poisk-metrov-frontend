<a class="news-card-primary" href="/articles/{{ $id }}">
    <img src="{{ $title_image_file_name }}">
    <header>
        <h6>
            {{ $title }}
            @include('buttons.arrow-right')
        </h6>
        @include('home-page.news.date')
    </header>
</a>
