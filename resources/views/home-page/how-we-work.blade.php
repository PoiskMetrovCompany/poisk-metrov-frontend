<div class="how-work-gallery work-base-container">
    @vite('resources/js/homePage/howWeWork.js')
    <div class="title-flex">
        <div class="title">
            Как мы работаем?
        </div>
    </div>
    <div class="how-work-gallery work-grid">
        <div class="how-work-gallery gallery-with-buttons">
            <div id="aboutus-gallery-buttons" class="arrow-buttons-container about-us">
                @include('buttons.arrow-left')
                @include('buttons.arrow-right')
            </div>
            <div id="aboutus-gallery" class="how-work-gallery images">
                <img class="how-work-gallery work-container" src="/placeholders/placeholder-24.jpeg" />
                <img class="how-work-gallery work-container" src="/placeholders/placeholder-25.jpeg" />
                <img class="how-work-gallery work-container" src="/placeholders/placeholder-26.jpeg" />
            </div>
        </div>
        <div class="how-work-gallery texts">
            <div class="how-work-gallery work-title">
                <div>Экскурсии по новостройкам</div>
                Раскрывать потенциал каждой локации, делая жизнь в доме источником положительных эмоций
            </div>
            <div class="how-work-gallery work-title" style="display: none">
                <div>Только актуальные цены</div>
                Данные на сайте обновляются ежедневно, чтобы вы могли быть уверены в их достоверности.
            </div>
            <div class="how-work-gallery work-title" style="display: none">
                <div>Недвижимость без комиссий</div>
                Мы ценим ваше время и финансовые ресурсы, поэтому предлагаем прозрачные условия покупки.
            </div>
        </div>
    </div>
</div>
