<div class="search-grid menu-header">
    <button type="button" @isset($backButtonId)
    id="{{ $backButtonId }}"        
    @endisset>
        <div class="icon arrow-tailless"></div>
        <span>Назад</span>
    </button>
    <div>{{ $headerTitle ?? 'Фильтр' }}</div>
    <button type="button" @isset($resetButtonId)
    id="{{ $resetButtonId }}" 
    @endisset>
        Сбросить</button>
</div>
