import { FileDownloader } from "../fileDownloader/FileDownloader";

document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("download-presentation-button");
    const url = button.getAttribute("link");
    const fileDownloader = new FileDownloader();
    const defaultButtonClass = button.className;
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

    button.addEventListener("click", async () => {
        if (fileDownloader.isLoading || button.className.includes("disabled")) {
            return;
        }

        const fileName = document.querySelector("h1").textContent;

        await fileDownloader.downloadFile(url, `${fileName}.pdf`);
    });
});
