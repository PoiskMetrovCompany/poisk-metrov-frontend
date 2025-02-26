<a type="card" href="/">
    @include('icons.icon', ['iconClass' => $icon, 'iconColor' => 'orange'])
    <h6>{{ $header }}</h6>
    <div type="line">
        <p>{{ $line1 }}</p>
        <p>{{ $line1Count }}</p>
    </div>
    <div type="line">
        <p>{{ $line2 }}</p>
        <p>{{ $line2Count }}</p>
    </div>
</a>
