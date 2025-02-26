{{-- Will be available in all scripts --}}
@auth
    <script>
        const isUserAuthorized = true;
    </script>
@endauth
@guest
    <script>
        const isUserAuthorized = false;
    </script>
@endguest
