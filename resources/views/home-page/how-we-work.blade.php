@php
$title = 'Как мы работаем?';
// 
$galleryList = [
    [
        'title' => 'Экскурсии по новостройкам',
        'description' => 'Раскрывать потенциал каждой локации, делая жизнь в доме источником положительных эмоций',
        'image' => '/placeholders/placeholder-24.jpeg'
    ],
    [
        'title' => 'Только актуальные цены',
        'description' => 'Данные на сайте обновляются ежедневно, чтобы вы могли быть уверены в их достоверности.',
        'image' => '/placeholders/placeholder-25.jpeg'
    ],
    [
        'title' => 'Недвижимость без комиссий',
        'description' => 'Мы ценим ваше время и финансовые ресурсы, поэтому предлагаем прозрачные условия покупки.',
        'image' => '/placeholders/placeholder-26.jpeg'
    ],
];
@endphp

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @vite('resources/css/swiper.override.css')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endsection

<div class="how-work-gallery work-base-container">

    <div class="title-flex">
        <div class="title">{{ $title }}</div>
    </div>
    <div class="swiper" style="width: 100%;">
        <div class="swiper-wrapper">
            @foreach($galleryList as $gallery)
                <div class="swiper-slide how-work-gallery work-grid">
                    <div class=" how-work-gallery gallery-with-buttons">
                        <div class=" how-work-gallery images">
                            <img class="how-work-gallery work-container"
                                 src="{{ $gallery['image'] }}"
                                 alt="{{ $gallery['title'] }}" />
                        </div>
                    </div>
                    <div class="how-work-gallery texts">
                        <div class=" how-work-gallery work-title">
                            <div>{{ $gallery['title'] }}</div>
                            {{ $gallery['description'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>

<script type="text/javascript">
    const swiper = new Swiper('.swiper', {
        direction: 'horizontal',
        loop: true,

        pagination: {
            el: '.swiper-pagination',
        },

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        scrollbar: {
            el: '.swiper-scrollbar',
        },
    });
</script>
