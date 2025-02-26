<fieldset id="{{ $id }}" class="input-fieldset">
    <legend class="input-legend">{{ $legend }}</legend>
    <div class="filters input-container">
        <div class="filters number-input grid">
            <div class="filters number-input number-input-flex">
                <div class="filters number-input min-max-flex">
                    <div class="filters number-input before">от</div>
                    <div id="min-value-display" class="filters number-input number">-</div>
                </div>
                <div class="filters number-input min-max-flex">
                    <div class="filters number-input before">до</div>
                    <div id="max-value-display" class="filters number-input number">-</div>
                </div>
            </div>
            @php
                $disabled = false;

                if ($min == $max) {
                    $max *= 2;
                    $disabled = true;
                }
            @endphp
            <range-selector disabled="{{ $disabled }}" id="{{ $id }}-selector"
                min-range="{{ $min }}" max-range="{{ $max }}" number-of-legend-items-to-show="4"
                hide-label hide-legend slider-color="#EC7D3F" circle-size="10px" circle-color="#EC7D3F"
                circle-border-color="#EC7D3F" circle-focus-border-color="#EC7D3F" style="max-height: 10px" />
        </div>
    </div>
</fieldset>
