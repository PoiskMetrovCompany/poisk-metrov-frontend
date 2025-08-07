@extends('export-pdf-layout')

@section('content')
    @include('export-pdf.shared.title-page')
    <div class="page-divider"></div>
    @foreach ($complexesToPdf as $complexData)
        @php
            $complex = $complexData['complex_data'];
            $statsData = $complexData['stats'];

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
        @include('export-pdf.real-estate.info-page')
        <div class="page-divider"></div>
    @endforeach
@endsection
