@extends('admin.layouts.app')
@section('content')
    <section class="w-100 h-100 d-flex justify-content-center align-items-center position-absolute top-0">
        <div class="card col-3 p-3 rounded-4">
            <h3 class="w-92-auto mb-4">
                <strong>Вход для администратора</strong>
            </h3>
            <span class="w-92-auto">Войдите в админ панель, чтобы иметь возможность управлять данными на сайте</span>
            @include('admin.components.forms.admin-login')
        </div>
    </section>
@endsection
