<div class="favorites want-compare base-container">
    @if (isset($title))
        <div>{{ $title }}</div>
    @else
        <div>Хотите сравнить выбранные планировки?</div>
    @endif
    <div>Войдите в личный кабинет</div>
    <div id={{ $id }} class="common-button wide">Личный кабинет</div>
</div>
