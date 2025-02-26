<div class="client-register base-container">
    @include('custom-elements.text-input', [
        'id' => 'client-add-surname',
        'legend' => 'Фамилия',
        'textOnly' => true,
    ])
    @include('custom-elements.text-input', [
        'id' => 'client-add-name',
        'legend' => 'Имя',
        'required' => true,
        'textOnly' => true,
    ])
    @include('custom-elements.text-input', [
        'id' => 'client-add-patronym',
        'legend' => 'Отчество',
        'textOnly' => true,
    ])
    <div dummy="true"></div>
    @include('custom-elements.phone-input', [
        'id' => 'client-add-phone',
        'phoneInputTitle' => 'Телефон',
        'required' => true,
        'value' => '+7',
    ])
    @include('custom-elements.text-input', [
        'id' => 'client-add-email',
        'legend' => 'E-mail',
        'type' => 'email',
    ])
</div>
