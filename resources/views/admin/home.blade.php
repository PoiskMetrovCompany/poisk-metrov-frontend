@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    @include('admin.components.dropdowns.cities-dropdown')
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    @include('admin.components.forms.admin-logout')
                </div>
            </div>
            <div class="row mt-72px">
                @include('admin.components.forms.synchronize-feed')
            </div>
            <div class="row mt-48px">
                @include('admin.components.dropdowns.filter-dropdown')
            </div>
            <div class="row">
                @include('admin.components.table', ['journals' => $journals])
            </div>
            <div class="row mt-40px">
                <div class="col-6">
                    @include('admin.components.pagination', ['paginator' => $journals])
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <form action="{{ route('admin.form.feed.destroy') }}" method="POST">
                        @csrf
                        <input type="hidden" id="hiddenSelectedCity" name="selectedCity" value="">
                        <button type="submit" class="btn btn-default btn-drop">Удалить все данные</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script type="module">
        window.addEventListener('DOMContentLoaded', function () {
            const savedCity = localStorage.getItem('selectedCity') || 'Новосибирск';
            document.getElementById('selectedCity').textContent = savedCity;
            document.getElementById('hiddenSelectedCity').value = savedCity;
        });
    </script>
@endsection
