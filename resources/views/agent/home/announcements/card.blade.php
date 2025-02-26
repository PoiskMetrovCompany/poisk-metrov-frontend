<div type="announcement">
    <div>
        <img src="{{ $image }}" />
        <div>
            <h5>{{ $name }}</h5>
            <h6>{{ $location }}</h6>
        </div>
    </div>
    <div>
        @include('icons.calendar')
        <div>{{ $date }}</div>
        <a href="/">Подробнее</a>
    </div>

</div>
