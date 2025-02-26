<div id="revert-ads-agreement" class="sub-menus background">
    <form autocomplete="off" id="revert-ads-form" class="sub-menus card">
        @csrf
        <div class="sub-menus form">
            <div class="sub-menus top-half">
                <div class="sub-menus top">
                    <div class="sub-menus header">
                        <div class="sub-menus title" style="width: 90%;">
                            Форма отказа от получения рекламных материалов
                        </div>
                    </div>
                    <div class="sub-menus close">
                        <div class="icon action-close d16x16 orange"></div>
                    </div>
                </div>
            </div>
            <div class="sub-menus inputs">
                @include('inputs.phone', ['required' => "*"])
            </div>
            <input type="submit" value='Отписаться' class="peinag button modal active">
        </div>
    </form>
</div>
