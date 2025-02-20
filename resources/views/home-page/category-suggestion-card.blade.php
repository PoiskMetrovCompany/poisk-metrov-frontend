@php
    $category =  $realEstateService->getRecommendedCategory();
    $categoryName = $category->category_name;
    $link = $realEstateService->getCatalogueLinkForCategory($category);

    $backgroundsForCategories = [
        'Элитные' => 1,
        'Новостройки у воды' => 2,
        'Хорошо под сдачу' => 3,
        'Быстрое заселение' => 4,
        'Рядом с метро' => 5,
        'Рядом парк и лес' => 6,
    ];
@endphp

<div class="apartment-suggestions category-suggestion-card"
    style="background-image: url('suggestions/{{ $backgroundsForCategories[$categoryName] }}.jpg')">
    <h6>
        @if ($categoryName == 'Элитные')
            Квартиры и апартаменты бизнес-класса
        @else
            {!! $categoryName !!}
        @endif
    </h6>
    @include('buttons.link', [
        'link' => $link,
        'subclass' => 'white',
        'buttonText' => 'Посмотреть варианты',
    ])
</div>
