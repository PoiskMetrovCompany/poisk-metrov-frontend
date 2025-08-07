@extends('document-layout', ['title' => 'Офисы'])

@section('content')
    @vite('resources/js/offices/loader.js')
    <div class="offices container">
        <div class="offices grid">
            <div class="offices left-item">
                <div class="offices items-container">
                    <h1 class="title first">Офисы продаж</h1>
                    <div class="offices buttons-grid">
                        @include('offices.city-button', [
                            'forCity' => 'novosibirsk',
                            'buttonId' => 'nsk-button',
                            'buttonText' => 'Новосибирск',
                        ])
                        @include('offices.city-button', [
                            'forCity' => 'st-petersburg',
                            'buttonId' => 'spb-button',
                            'buttonText' => 'Санкт-Петербург',
                        ])
                    </div>
                    @include('offices.office-list', [
                        'containerId' => 'nsk',
                        'forCity' => 'novosibirsk',
                        'officeListTemplate' => 'offices.novosibirsk-list',
                    ])
                    @include('offices.office-list', [
                        'containerId' => 'spb',
                        'forCity' => 'st-petersburg',
                        'officeListTemplate' => 'offices.st-petersburg-list',
                    ])
                </div>
            </div>
            @include('offices.map', [
                'mapId' => 'map-nsk',
                'forCity' => 'novosibirsk',
            ])
            @include('offices.map', [
                'mapId' => 'map-spb',
                'forCity' => 'st-petersburg',
            ])
        </div>
        @include('offices.message')
    </div>
    @include('menus.forms.make-meeting')
@endsection
