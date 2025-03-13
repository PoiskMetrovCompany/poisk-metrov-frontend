<div class="apartment-card__grid">
    {{-- TODO: Вынести в компонент --}}
    @foreach($menuApartmentTitle as $item)
        <div class="apartment-container__label">{{ $item }}</div>
    @endforeach
    {{-- END --}}
</div>
