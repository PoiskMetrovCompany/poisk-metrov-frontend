<div class="revervation_user__card-container">
    <div class="revervation_user__card-body">
        @foreach($users as $item => $key)
            <section class="revervation_user__card-item">
                        <span class="revervation_user__card-title">
                            <strong>{{ $key['entity'] }}</strong>
                        </span>
                <section class="evervation_user__card-data">
                    <p class="revervation_user__card-label"><span>ФИО</span></p>
                    <p class="revervation_user__card-description">{{ $key['fio'] }}</p>
                </section>
                <section class="evervation_user__card-data">
                    <p class="revervation_user__card-label"><span>Телефон</span></p>
                    <p class="revervation_user__card-description">{{ $key['phone'] }}</p>
                </section>
                <section class="evervation_user__card-data">
                    <p  class="revervation_user__card-label"><span>Email</span></p>
                    <p class="revervation_user__card-description">{{ $key['email'] }}</p>
                </section>
            </section>
        @endforeach
    </div>
</div>
