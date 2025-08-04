export let isCityMapOpen = false;

export function openMap(mapElement) {
    mapElement.className += " visible";
    isCityMapOpen = true;

    const mobileBottomBar = document.querySelector("mobile-toolbar");
    mobileBottomBar.style.zIndex = 8;

    if (('ontouchstart' in document.documentElement)) {
        document.body.requestFullscreen();
    }

    document.body.style.overscrollBehaviorY = "contain";
}

export function closeMap(mapElement, defaultClassName) {
    mapElement.className = defaultClassName;
    isCityMapOpen = false;

    const mobileBottomBar = document.querySelector("mobile-toolbar");
    mobileBottomBar.style.zIndex = "";

    if (document.fullscreenElement != null) {
        document.exitFullscreen();
    }

    document.body.style.overscrollBehaviorY = "";
}