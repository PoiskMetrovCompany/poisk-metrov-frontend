import { CRMForm } from "../crm/CRMForm";

document.addEventListener("DOMContentLoaded", () => {
    const quiz = document.getElementById("quiz-section");

    if (!quiz) {
        return;
    }

    const defaultClassName = "quiz step";
    const steps = Array.from(quiz.getElementsByClassName(defaultClassName));
    const stepTitles = ["Ежемесячный платеж", "Первоначальный взнос", "Количество комнат"];
    const options = ["\r\nКвиз"];

    steps.forEach((step, i) => {
        const stepButtons = step.querySelectorAll("button");

        stepButtons.forEach(button => {
            button.addEventListener("click", () => {
                options.push(`${stepTitles[i]}: ${button.textContent.trim()}`);
                step.className = defaultClassName;
                steps[i + 1].className += " visible";
            });
        });
    });

    const crmForm = new CRMForm(
        "quiz-form",
        undefined,
        "/api/leave-request-without-name",
        ["phone"],
        () => {
            return options.join("\r\n");
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";
        }
    );
});