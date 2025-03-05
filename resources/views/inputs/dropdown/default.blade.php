@php
    $selectIdentifier = \Illuminate\Support\Str::uuid()->toString();

    if (
        (!empty($nameInputTitle) && $nameInputTitle === 'Документ о доходах') ||
        (!empty($nameInputTitle) && $nameInputTitle === 'Вид трудового договора')
    ) {
        $classList = 'row-80';
    }
@endphp

<div class="input-container ">
    <fieldset id="fieldset-{{ $selectIdentifier }}" class="input-fieldset">
        <legend id="legend-{{ $selectIdentifier }}" class="input-legend">{{ $nameInputTitle ?? '' }}<span
                class="red-highlight">{{ $required ?? '' }}</span></legend>
        <div class="input-wrapper">
            <div id="{{ $selectIdentifier }}" class="filter base-container dropdown" tabindex="-1">
                <span> {{ $placeholder ?? 'Выбрать' }}</span>
                <div class="icon arrow-tailless grey5" style="rotate: 0deg;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              clip-rule="evenodd"
                              d="M5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289L12 14.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.3166 17.0976 11.6834 17.0976 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289Z"
                              fill="#9E9E9E"/>
                    </svg>
                </div>
                <div class="custom-dropdown base-container" allowmultiple="1">
                    @foreach($values as $item)
                        @include('inputs.dropdown.fields.text-item', ['item' => $item])
                    @endforeach
                </div>
            </div>

        </div>
    </fieldset>
</div>

<script>
    document.getElementById('{{ $selectIdentifier }}').addEventListener('click', function () {
        const selectIdentifier = '{{ $selectIdentifier }}'; // Локальная переменная
        const colorActive = '#0436B6';
        const rotateActive = '180deg';
        const colorDefault = '#D2D2D2';
        const rotateDefault = '0deg';
        const className = 'open';

        const dropdownContainer = document.getElementById(selectIdentifier);

        let fieldsetDropdown = document.getElementById(`fieldset-${selectIdentifier}`);
        let legendDropdown = document.getElementById(`legend-${selectIdentifier}`);
        let customDropdown = dropdownContainer.querySelector('.custom-dropdown');
        let arrowTailless = dropdownContainer.querySelector('.arrow-tailless');

        if (!customDropdown.classList.contains(className)) {
            fieldsetDropdown.style.borderColor = colorActive;
            legendDropdown.style.color = colorActive;
            customDropdown.classList.add(className);
            arrowTailless.style.transform = `rotate(${rotateDefault})`;
        } else {
            fieldsetDropdown.style.borderColor = colorDefault;
            legendDropdown.style.color = '';
            customDropdown.classList.remove(className);
            arrowTailless.style.transform = `rotate(${rotateActive})`;
        }
    });
</script>
