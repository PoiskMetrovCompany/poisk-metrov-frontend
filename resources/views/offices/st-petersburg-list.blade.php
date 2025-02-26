@include('offices.dropdown', [
    'address' => 'ул. Парфёновская, 12, <br> этаж 5, офис 509, этаж 6, офис 609',
])
@include('offices.office-info', [
    'image' => 'resources/assets/pictures/parfenovskaya.png',
    'address' => 'ул. Парфёновская, 12, <br> этаж 5, офис 509, этаж 6, офис 609',
    'schedule' => 'Ежедневно с 10:00 до 21:00',
    'phone' => '+7 (800) 444-40-45',
    'buttonId' => 'make-meeting-parfenovskaya',
])
