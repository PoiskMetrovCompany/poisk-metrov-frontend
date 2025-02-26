<div class="stats">
    @foreach ($statsData as $stats)
        @php
            $statTitle = preg_replace('/[^0-9]/', '', $stats['name']) . '-комнатные';

            if ($stats['name'] == 'студ.') {
                $statTitle = 'Студии';
            }
        @endphp
        @include('export-pdf.real-estate.parts.stat', [
            'statTitle' => $statTitle,
            'count' => $stats['count'],
            'minSquare' => $stats['min-square'],
            'minPrice' => $stats['min-price'],
        ])
        <div class="divider"></div>
    @endforeach
</div>
