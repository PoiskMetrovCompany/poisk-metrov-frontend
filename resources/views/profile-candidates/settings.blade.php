@extends('profile-candidates.layout.app')

@section('content')
    <section style="min-height: 0; flex-wrap: wrap;">
        <div class="formRow justify-flex-start w-60">
            <h2>Настройки анкеты</h2>

        </div>
        <div class="center-card big w-60">

            <div class="formRow">
                <h3 style="text-align: left;">Роли вакансий</h3>
            </div>
            <div class="formRow" style="margin-top: 0">
                <h4 style="text-align: left;">Роли вакансий, которые отображаются в анкете кандидатов</h4>
            </div>

            <div class="formRow justify-flex-start" style = "flex-wrap: wrap; gap: 1rem">
                <div class="roleItem">Агент по недвижимости</div>
                <div class="roleItem">Ипотечный специалист</div>
                <div class="roleItem">HR</div>
                <div class="roleItem">Юрист</div>
                <div class="roleItem">Разработчик</div>
                <div class="roleItem">Дизайнер</div>
                <div class="roleItem">Агент по недвижимости</div>
                <div class="roleItem">Ипотечный специалист</div>
                <div class="roleItem">HR</div>
                <div class="roleItem">Юрист</div>
                <div class="roleItem">Разработчик</div>
                <div class="roleItem">Дизайнер</div>
            </div>
            <div class = "formRow justify-flex-start" style = "margin-top: 0;">
                <button class="formBtn small btn-active" disabled="true">
                    Добавить роль
                </button>
                <button class="formBtn small btn-inactive" disabled="true">
                    Редактировать
                </button>
            </div>
        </div>
    </section>
@endsection
