@php
    require_once resource_path('views/reservation/meta/i18n.ru.php');
@endphp

@extends('document-layout', [
    'title' => $title,
])

@section('content')
    <div class="base-container">
        <div class="title first">{{ $title }}</div>
        <div class="full-row">
            @include('reservation.components._apartmentCard', [
                'menuApartmentTitle' => $menuApartmentTitle,
                'countApartments' => count($apartmentList),
                'apartmentsList' => $apartmentList
            ])
        </div>
        <section class="revervation__grid">
            @include('reservation.components.cardUser', ['users' => $users])
            <section class="reservation__layout">
{{--                @include('reservation.components._apartmentPrice', [--}}
{{--                    'name' => $interaction['apartment']->h1,--}}
{{--                    'price' => $interaction['apartment']->price--}}
{{--                ])--}}

{{--                @include('reservation.components._menuFormBar', ['menuLists' => $menuLists])--}}
{{--                @include('reservation.components._formActions', [--}}
{{--                    'bookings' => $bookings,--}}
{{--                    'accordions' => $accordions--}}
{{--                ])--}}
{{--                @include('reservation.components._add_co-borrower', [--}}
{{--                    'borrower' => $borrower--}}
{{--                ])--}}
            </section>
        </section>
    </div>

    @vite([
        'resources/js/reservation/panelControlReservation.js',
        'resources/js/reservation/index.js'
    ])
@endsection
