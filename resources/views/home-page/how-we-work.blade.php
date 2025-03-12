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

<div class="how-work-gallery work-base-container">
    @vite('resources/js/homePage/howWeWork.js')
    <div class="title-flex">
        <div class="title">{{ $title }}</div>
    </div>
    <div class="how-work-gallery work-grid">
        <div class="how-work-gallery gallery-with-buttons">
            <div id="aboutus-gallery-buttons" class="arrow-buttons-container about-us">
                @include('buttons.arrow-left')
                @include('buttons.arrow-right')
            </div>
            <div id="aboutus-gallery" class="how-work-gallery images">
                @foreach($galleryList as $gallery)
                    <img class="how-work-gallery work-container"
                         src="{{ $gallery['image'] }}"
                         alt="{{ $gallery['title'] }}" />
                @endforeach
            </div>
        </div>
        <div class="how-work-gallery texts">
            @foreach($galleryList as $gallery)
                <div class="how-work-gallery work-title">
                    <div>{{ $gallery['title'] }}</div>
                    {{ $gallery['description'] }}
                </div>
            @endforeach
        </div>
    </div>
</div>
