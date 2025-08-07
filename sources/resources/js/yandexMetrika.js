let timer = undefined;

//От метрики сайт сильно тормозит. Выключаем хотя бы локально
if (location.hostname !== "localhost" && location.hostname !== "127.0.0.1") {
    document.addEventListener("DOMContentLoaded", () => {
        //Загружаем сразу для бота яндекса
        if (navigator.userAgent.indexOf('YandexMetrika') > -1) {
            loadMetrica();
        } else {
            window.addEventListener('scroll', loadMetrica, { passive: true });
            window.addEventListener('touchstart', loadMetrica);
            document.addEventListener('mouseenter', loadMetrica);
            document.addEventListener('click', loadMetrica);
            document.addEventListener('DOMContentLoaded', loadFallback);
        }

        function loadFallback() {
            timer = setTimeout(loadMetrica, 1000);
        }
    });
} else {
    console.log("Yandex metrika disabled locally");
}

let isMetricaLoaded = false;

function loadMetrica() {
    if (isMetricaLoaded) {
        return;
    }

    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        for (var j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    ym(96814411, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true,
        ecommerce: "dataLayer"
    });

    isMetricaLoaded = true;

    console.log("Yandex Metrika loaded");

    window.removeEventListener('scroll', loadMetrica);
    window.removeEventListener('touchstart', loadMetrica);
    document.removeEventListener('mouseenter', loadMetrica);
    document.removeEventListener('click', loadMetrica);

    if (timer) {
        clearTimeout(timer);
        timer = undefined;
    }
}