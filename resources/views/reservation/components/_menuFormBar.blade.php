@php
    $chatName = 'Чат';
    $menuLists = [
        [
            'name' => 'Бронирование',
            'status' => 'active'
        ],
        [
            'name' => 'Заявка на ипотеку',
            'status' => 'disabled'
        ],
        [
            'name' => 'Ипотечное решение',
            'status' => 'disabled'
        ],
        [
            'name' => 'Договор',
            'status' => 'disabled'
        ],
        [
            'name' => $chatName,
            'status' => 'disabled'
        ],
    ];
@endphp

<div class="actions-navbar">
    <div class="actions-navbar__container actions-navbar__min">
        @foreach($menuLists as $item => $key)
            <div class="
                actions-navbar__item
                actions-navbar__point
                @if ($key['status'] === 'active') actions-navbar__active @endif
                @if ($key['name'] === $chatName) actions-navbar__chat-active @endif
            ">
                {{ $key['name'] }}
                @if($key['name'] != $chatName && $key['status'] === 'active')
                    <div class="actions-navbar__active-indicator"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
