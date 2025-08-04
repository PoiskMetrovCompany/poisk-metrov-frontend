@if (isset($description) && $description != null)
    @php
        $offset = 0;
        $description = trim($description);

        for ($i = 0; $i < 3; $i++) {
            if (strlen($description) > $offset) {
                $offset = strpos($description, PHP_EOL, $offset);
            } else {
                break;
            }
        }

        $shortedDescription = substr($description, 0, $offset);
    @endphp
    @vite('resources/js/realEstate/showMore.js')
    <div class="about-estate base-container">
        <h2 class="real-estate title">О комплексе</h2>
        <div class="about-estate description-grid">
            <div id="complex-description" class="about-estate description-grid description-text">
                <div>{{ $shortedDescription }}</div>
                <div id="show-more" class="about-estate description-grid underlined">Подробнее</div>
            </div>
            <div id="complex-description-full" class="about-estate description-grid description-text"
                style="display: none">
                <div>{{ $description }}</div>
                <div id="hide-more" class="about-estate description-grid underlined">Скрыть</div>
            </div>
        </div>
    </div>
@endif
