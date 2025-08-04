document.addEventListener("DOMContentLoaded", function () {
    const footer = document.getElementById("have-questions");

    const gotQuestionsContainer = footer.querySelector('.footer.got-questions.container');
    const dividerElements = footer.querySelectorAll('.footer.divider');
    const subscribeContainers = footer.querySelectorAll('.footer.subscribe-to-telegram.container');
    const mobileSubscribeContainer = footer.querySelector('.footer.subscribe-to-telegram.container.mobile');

    disableElement(gotQuestionsContainer);
    disableElements(dividerElements);
    disableElements(subscribeContainers);
    disableElement(mobileSubscribeContainer);

    function disableElement(element) {
        if (element)
            element.style.display = 'none';
    }

    function disableElements(element) {
        if (element)
            element.forEach((element) => element.style.display = 'none');
    }
});