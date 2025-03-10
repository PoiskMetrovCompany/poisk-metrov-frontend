@php
    require_once resource_path('views/reservation/meta.php');
@endphp

@extends('document-layout', [
    'title' => 'Мои&nbsp;брони',
])

@section('content')
    <div class="base-container">
        <div class="title first">{{ $contentTitle }}</div>
        <div class="full-row">
            @include('reservation.components._apartmentCard', [
                'menuApartmentTitle' => $menuApartmentTitle,
                'countApartments' => $countApartments,
                'apartmentsList' => $apartmentsList
            ])
        </div>
        <section class="revervation__grid">
            @include('reservation.components.cardUser', ['users' => $users])
            <section class="reservation__layout">
                @include('reservation.components._apartmentPrice', [
                    'name' => 'Квартира-студия в ЖК Брусника, 30.2 м², этаж 9',
                    'price' => '9 615 862'
                ])

                @include('reservation.components._menuFormBar', ['menuLists' => $menuLists])
                @include('reservation.components._formActions', [
                    'bookings' => $bookings,
                    'accordions' => $accordions
                ])
                @include('reservation.components._add_co-borrower', [
                    'borrower' => $borrower
                ])
            </section>
        </section>
    </div>

    @vite([
        'resources/js/reservation/panelControlReservation.js',
        'resources/js/reservation/index.js'
    ])
@endsection
