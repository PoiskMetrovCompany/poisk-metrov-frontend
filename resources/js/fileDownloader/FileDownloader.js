import { downloadFile } from "../downloadFile";
import axios from "axios";

export class FileDownloader {
    isLoading = false;
    intervalRate = 400;

    async downloadFile(url, fileName) {
        if (this.isLoading) {
            return;
        }

        const interval = setInterval(() => {
            this.onInterval();
        }, this.intervalRate);

        this.onStartDownload();
        this.isLoading = true;
        const response = await axios.get(url, { responseType: 'blob' });
        this.isLoading = false;
        this.onFinishDownload();
        clearInterval(interval);
        downloadFile(URL.createObjectURL(new Blob([response.data])), fileName);
    }

    onInterval() {

    }

    onStartDownload() {

    }

    onFinishDownload() {

    }
}
