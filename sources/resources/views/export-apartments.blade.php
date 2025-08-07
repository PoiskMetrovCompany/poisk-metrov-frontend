@extends('export-pdf-layout')

@section('content')
    @include('export-pdf.shared.title-page')
    <div class="page-divider"></div>
    @foreach ($apartments as $apartmentGroup)
        @php
            $complex = $apartmentGroup['complex_data'];

            //Превращаем картинки в base64 чтобы потом они отрендерились в файле
            if (count($complex['gallery']) > 0) {
                $preview = $fileService->getFileAsBase64($complex['gallery'][0]);
            }
        @endphp
        <script>
            previews['{{ $complex['code'] }}'] = '{{ $preview }}';
        </script>
        @include('export-pdf.shared.description')
        <div class="page-divider"></div>
        @include('export-pdf.shared.location')
        <div class="page-divider"></div>
        @foreach ($apartmentGroup['apartments'] as $apartment)
            @include('export-pdf.apartment.info-page')
            <div class="page-divider"></div>
        @endforeach
    @endforeach
@endsection
