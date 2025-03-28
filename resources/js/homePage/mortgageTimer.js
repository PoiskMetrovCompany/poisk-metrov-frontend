import axios from 'axios';

let targetDate = null;

async function queryCbrDate() {
    try {
        const response = await axios.get('/api/v1/cbr/actual-date');
        const dateResponse = new Date(response.data);
        console.log(dateResponse)
        targetDate = dateResponse;
    } catch (error) {
        console.error("Ошибка при выполнении запроса:", error);
    }
}
queryCbrDate();

document.addEventListener("DOMContentLoaded", async () => {
    while (!targetDate) {
        await new Promise(resolve => setTimeout(resolve, 100));
    }

    const currentDate = new Date(Date.now());
    const targetYear = targetDate.getFullYear();
    const targetMonth = targetDate.getMonth() + 1;
    const targetDay = targetDate.getDate();

    console.log(targetDay);
    console.log(targetMonth);
    console.log(targetYear);

    const mortgageCard = document.querySelector(".mortgage-card.base-container");
    const times = mortgageCard.querySelectorAll(".mortgage-card.time");
    const days = times[0];
    const hours = times[2];
    const minutes = times[4];

    function updateTime() {
        const currentDate = new Date(Date.now());
        const remainingTime = targetDate - currentDate;

        if (remainingTime <= 0) {
            clearInterval(timer);
            return;
        }

        const remainingDays = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
        const remainingHours = new Date(remainingTime).getUTCHours();
        const remainingMinutes = new Date(remainingTime).getUTCMinutes();

        days.textContent = remainingDays;
        hours.textContent = addZeroIfNecessary(remainingHours);
        minutes.textContent = addZeroIfNecessary(remainingMinutes);
    }

    function addZeroIfNecessary(value) {
        return value.toString().padStart(2, "0");
    }

    updateTime();

    const timer = setInterval(() => {
        updateTime();
    }, 60000);
});
