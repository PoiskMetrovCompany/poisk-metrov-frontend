document.addEventListener("DOMContentLoaded", () => {
    let currentDate = new Date(Date.now());
    const targetYear = 2025;
    const targetMonth = 1;
    const targetDay = 14;
    const targetDate = new Date(targetYear, targetMonth, targetDay);
    const mortgageCard = document.querySelector(".mortgage-card.base-container");
    const times = mortgageCard.querySelectorAll(".mortgage-card.time");
    const days = times[0];
    const hours = times[2];
    const minutes = times[4];

    function updateTime() {
        currentDate = new Date(Date.now());

        const remainingTime = new Date(targetDate - currentDate);
        days.textContent = Math.round(remainingTime.getTime() / (1000 * 3600 * 24));
        hours.textContent = remainingTime.getHours();
        minutes.textContent = remainingTime.getMinutes();
        addZeroIfNecessary(hours);
        addZeroIfNecessary(minutes);

        function addZeroIfNecessary(element) {
            if (element.textContent.length == 1) {
                element.textContent = "0" + element.textContent;
            }
        }
    }

    updateTime();

    const timer = setInterval(() => {
        updateTime();
    }, 60000);
});