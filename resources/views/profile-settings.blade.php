@extends('document-layout', [
    'title' => 'Профиль',
    'excludeNoIndexing' => true,
])

@section('pagescript')
    @vite('resources/js/profile/profile.js')
@endsection

@section('content')
    <div class="profile base-container">
        <div class="title first">Настройки профиля</div>
        <div class="profile container" @auth style="display: grid" @endauth>
            <form autocomplete="off" class="profile form" id="profile-form">
                @csrf
                <div class="profile form inputs-container">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    @include('inputs.phone-disabled', ['required' => '*', 'value' => $user->phone])
                    @include('inputs.name', ['required' => '*'])
                    @include('inputs.patronymic', ['value' => $user->patronymic])
                    @include('inputs.surname', ['placeholder' => '', 'value' => $user->surname])
                    @include('inputs.email', ['value' => $user->email])
                </div>
                <input type="submit" class="common-button" value="Сохранить изменения">
            </form>
            <img src="{{ Vite::asset('resources/assets/content/content-profile.svg') }}" class="profile image">
        </div>
    </div>
    <div class="title first" @auth style="display: none" @endauth>Вы не вошли в личный кабинет</div>
    @include('catalogue.get-free-catalogue')
@endsection
