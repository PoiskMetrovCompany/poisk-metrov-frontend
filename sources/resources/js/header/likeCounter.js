document.addEventListener("likesUpdated", (event) => {
    const counters = Array.from(document.querySelectorAll(".header.counter"));

    counters.forEach(counter => {
        if (counter) {
            counter.textContent = event.newCount;
            counter.style.display = event.newCount > 0 ? "grid" : "none";
        }
    });
});