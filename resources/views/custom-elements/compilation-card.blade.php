<compilation-card>
    <img src="{{ $image }}">
    <div>
        <h6>{{ $name }}</h6>
        <p>
            {{ $count }}
            @if ($count == 1)
                квартира
            @elseif($count <= 4)
                квартиры
            @else
                квартир
            @endif
        </p>
        <div>
            @include('buttons.link', [
                'link' => '/',
                'buttonText' => 'Подробнее',
                'subclass' => 'white',
            ])
            <a href="/">
                @include('icons.icon', ['iconClass' => 'download', 'iconColor' => 'grey5'])
            </a>
        </div>
    </div>
</compilation-card>
