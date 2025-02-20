import { FileDownloader } from "../fileDownloader/FileDownloader";

function changeCategory() {
    const plans = document.getElementById("plans");
    const complexes = document.getElementById("complexes");

    if (plans && complexes) {
        const plansIcon = Array.from(plans.getElementsByTagName("div"))[0];
        const plansContainer = document.getElementById("plans-grid");
        const complexesIcon = Array.from(complexes.getElementsByTagName("div"))[0];
        const complexesContainer = document.getElementById("complexes-grid");

        plans.addEventListener("click", () => {
            selectPlansCategory(plans, plansIcon, complexes, complexesIcon, plansContainer, complexesContainer);
        });

        complexes.addEventListener("click", () => {
            selectBuildingsCategory(plans, plansIcon, complexes, complexesIcon, plansContainer, complexesContainer);
        });
    }
}

function selectPlansCategory(plans, plansIcon, complexes, complexesIcon, plansContainer, complexesContainer) {
    plans.className = "common-button";
    plansIcon.className = "icon content-plan d28x28 white";
    complexes.className = "common-button white1";
    complexesIcon.className = "icon buildings d28x28 black";
    plansContainer.style.display = "grid";
    complexesContainer.style.display = "none";

    currentSelected = 'plan';
}

function selectBuildingsCategory(plans, plansIcon, complexes, complexesIcon, plansContainer, complexesContainer) {
    complexes.className = "common-button";
    plansIcon.className = "icon content-plan d28x28 black"
    plans.className = "common-button white1";
    complexesIcon.className = "icon buildings d28x28 white";
    complexesContainer.style.display = "grid";
    plansContainer.style.display = "none";

    currentSelected = 'building';
}

let currentSelected = 'plan';

function loadPresentationButton(buttonId) {
    const plansContainer = document.getElementById("plans-grid");
    const button = document.getElementById(buttonId);

    if (!plansContainer || !button) {
        return;
    }

    const fileDownloader = new FileDownloader();
    const defaultButtonClass = button.className;
    const areStylesEqual = (plansContainer.style.display == 'none');
    const apartmentsUrl = '/get-favorite-apartments-presentation';
    const buildingsUrl = '/get-favorite-buildings-presentation';
    currentSelected = areStylesEqual ? 'building' : 'plan';
    let dots = 0;

    fileDownloader.onInterval = () => {
        button.textContent = "Загружаем";
        dots++;

        for (let i = 0; i < dots; i++) {
            button.textContent += '.';
        }

        if (dots > 2) {
            dots = 0;
        }
    }

    fileDownloader.onStartDownload = () => {
        button.className += " disabled";
    }

    fileDownloader.onFinishDownload = () => {
        button.className = defaultButtonClass;
        button.textContent = "Скачать";
    }

    function updateButtonForCounts() {
        if (currentSelected == 'plan') {
            button.className = defaultButtonClass;

            if (plansCount == 0) {
                button.className += " disabled";
            }
        }

        if (currentSelected == 'building') {
            button.className = defaultButtonClass;

            if (buildingsCount == 0) {
                button.className += " disabled";
            }
        }
    }

    updateButtonForCounts();

    document.addEventListener("likesUpdated", (event) => {
        buildingsCount = event.newBuildingCount
        plansCount = event.newPlanCount;
        updateButtonForCounts();
    });
    document.getElementById("plans").addEventListener("click", () => updateButtonForCounts());
    document.getElementById("complexes").addEventListener("click", () => updateButtonForCounts());

    button.addEventListener("click", async () => {
        if (fileDownloader.isLoading || button.className.includes("disabled")) {
            return;
        }

        const url = currentSelected == 'plan' ? apartmentsUrl : buildingsUrl;
        const fileName = currentSelected == 'plan' ? "Избранные планировки" : "Избранные ЖК";

        await fileDownloader.downloadFile(url, `${fileName}.pdf`);
    });
}

//Order is important here
document.addEventListener("DOMContentLoaded", () => changeCategory());
document.addEventListener("DOMContentLoaded", () => {
    loadPresentationButton("download-favorite-presentation-button");
    loadPresentationButton("download-favorite-presentation-button-mobile");
});
