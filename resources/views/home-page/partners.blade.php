@php
    $cityPartners['novosibirsk'] = [
        'rastsvetay',
        'meta',
        'energo-montazh',
        'acacia',
        'elka',
        'brusnika',
        'scandia',
        'vira-stroy',
        'yasniy-bereg',
        'dom-stroy',
        'kpd-gazstroy',
        'soyuz',
        'noviy-mir',
    ];
    $cityPartners['st-petersburg'] = [
        'akvilon',
        'arsenal',
        'cdc',
        'fsk',
        'glorax',
        'kvs',
        'lcp',
        'pik',
        'lenstroy',
        'polis',
        'rbi',
        'setl',
        'terminal',
    ];
@endphp

@if (key_exists($selectedCity, $cityPartners))
    <div class="base-container">
        <div class="title">
            Партнёры, которые нам <span class="link-highlight">доверяют</span>
        </div>
        <div class="partners grid" id="partners-grid-novosibirsk"
            style="display: {{ $selectedCity == 'novosibirsk' ? 'grid' : 'none' }}">
            @foreach ($cityPartners['novosibirsk'] as $item)
                <div class="partners partner-card">
                    <div @class(['partner-icons-novosibirsk ' . $item . ' bg'])></div>
                </div>
            @endforeach
        </div>
        <div class="partners grid" id="partners-grid-st-petersburg"
            style="display: {{ $selectedCity == 'st-petersburg' ? 'grid' : 'none' }}">
            @foreach ($cityPartners['st-petersburg'] as $item)
                <div class="partners partner-card">
                    <div @class(['partner-icons-st-petersburg ' . $item . ' bg'])></div>
                </div>
            @endforeach
        </div>
    </div>
@endif
