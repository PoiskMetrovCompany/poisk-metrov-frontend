<div id="{{$id}}-custom-dropdown" tabindex="-1" class="static-custom-dropdown container">
    <input type="hidden" id="custom-dropdown-id" value="{{$id}}">
    @isset($title)
        <div class="static-custom-dropdown title">{{$title}}</div>
    @endisset
    <div class="static-custom-dropdown select-header">
        <div class="static-custom-dropdown placeholder">{{$preview ?? $items[0]}}</div>
        <div class="icon arrow-tailless grey5"></div>
    </div>
    <div class="static-custom-dropdown base-container">
        @foreach ($items as $item)
            <div class="static-custom-dropdown item">
                <div class="static-custom-dropdown item-container">{{$item}}</div>
            </div>
        @endforeach
    </div>        
</div>