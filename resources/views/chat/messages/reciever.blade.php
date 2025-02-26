<div class="reciever message base-container">
    <img src="{{ $profilePicUrl }}" />
    <div class="message site-logo-as-icon" style="display: none">
        <img src="{{ Vite::asset('resources/assets/site-logo.svg') }}" />
    </div>
    <div class="reciever message text-container">
        <div class="message reciever name-and-text">
            <h6>{{ $recieverName }}</h6>
            <div>{{ $messageText }}</div>
        </div>
        <div class="message time">{{ $time }}</div>
    </div>
</div>
