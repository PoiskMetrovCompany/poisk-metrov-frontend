<estate-categories>
    @include('agent.home.categories.card', [
        'header' => 'Квартиры с комиссией 3,5%<br>',
    ])
    @include('agent.home.categories.card', [
        'header' => 'Новостройки<br> у воды',
        'category' => 'Новостройки у воды',
    ])
    @include('agent.home.categories.card', [
        'header' => 'Хорошо<br> под сдачу',
        'category' => 'Хорошо под сдачу',
    ])
    @include('agent.home.categories.card', [
        'header' => 'Быстрое<br> заселение',
        'category' => 'Быстрое заселение',
    ])
    @include('agent.home.categories.card', [
        'header' => 'Рядом с метро',
        'icon' => 'content-metro',
        'category' => 'Рядом с метро',
    ])
    @include('agent.home.categories.card', [
        'header' => 'Рядом с парком<br> и лесом',
        'icon' => 'content-tree',
        'category' => 'Рядом парк и лес',
    ])
    @include('agent.home.categories.card', [
        'header' => 'Элитное жилье',
        'category' => 'Элитные',
    ])
</estate-categories>
