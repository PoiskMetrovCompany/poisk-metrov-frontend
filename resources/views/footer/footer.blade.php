<footer id="have-questions" class="footer base-container common-padding">
    <div class="footer got-questions container">
        @include('footer.got-questions')
        @include('footer.leave-contacts-form')
    </div>
    <div class="footer divider"> </div>
    @include('footer.sub-to-telegram')
    @include('footer.sub-to-telegram-mobile')
    <div class="footer divider"> </div>
    <div class="footer contacts container">
        <div class="footer contacts info container">
            @include('footer.our-contacts')
            <div class="footer contacts info address-media grid">
                <div class="footer contacts info address-media column">
                    @include('footer.address')
                </div>
                <div class="footer contacts info address-media column">
                    @include('footer.social-media')
                </div>
            </div>
        </div>
        @include('footer.copyright')
    </div>
</footer>
