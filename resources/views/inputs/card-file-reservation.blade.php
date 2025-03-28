<section class="card-file">
    <div class="card-file__upload">
        <section class="card-file__upload-70">
            <p>Загрузите или перетащите сюда</p>
            <div id="fileName"></div>
        </section>
        <section class="card-file__upload-30">
            <input type="file" id="file-input" style="display: none;">
            <button id="windowUploadFile" class="action-button action-button__ficon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="24" height="24" rx="12" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6666 11.3346H12.6666V7.33464C12.6666 6.96597 12.368 6.66797 12 6.66797C11.632 6.66797 11.3333 6.96597 11.3333 7.33464V11.3346H7.33329C6.96529 11.3346 6.66663 11.6326 6.66663 12.0013C6.66663 12.37 6.96529 12.668 7.33329 12.668H11.3333V16.668C11.3333 17.0366 11.632 17.3346 12 17.3346C12.368 17.3346 12.6666 17.0366 12.6666 16.668V12.668H16.6666C17.0346 12.668 17.3333 12.37 17.3333 12.0013C17.3333 11.6326 17.0346 11.3346 16.6666 11.3346Z" fill="#EC7D3F"/>
                </svg>
                Загрузить файл
            </button>
            <button class="action-button action-button-outline">Загрузить с телефона</button>
        </section>
    </div>
</section>

<script type="text/javascript">
    const fileInput = document.getElementById('file-input');
    const uploadButton = document.getElementById('windowUploadFile');

    uploadButton.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        if (files.length > 0) {
            document.getElementById('fileName').insertAdjacentHTML("beforeend", `
                 <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.9308 5.63816L11.4757 0.183086C11.4176 0.125043 11.3487 0.0789999 11.2729 0.0475859C11.1971 0.0161719 11.1158 2.139e-06 11.0337 0L2.88709 0C1.78381 0 0.88623 0.897578 0.88623 2.00086V17.9991C0.88623 19.1024 1.78381 20 2.88709 20H15.1129C16.2162 20 17.1138 19.1024 17.1138 17.9991V6.08008C17.1138 5.99801 17.0976 5.91674 17.0662 5.84092C17.0348 5.76509 16.9888 5.6962 16.9308 5.63816ZM11.6587 2.13391L14.9799 5.45512H11.6587V2.13391ZM15.1129 18.75H2.88709C2.47307 18.75 2.13623 18.4132 2.13623 17.9991V2.00086C2.13623 1.58684 2.47307 1.25 2.88709 1.25H10.4087V6.08008C10.4087 6.42523 10.6886 6.70508 11.0337 6.70508H15.8638V17.9991C15.8638 18.4132 15.527 18.75 15.1129 18.75ZM13.4688 10.3838C13.4688 10.729 13.1889 11.0088 12.8438 11.0088H5.15627C4.81111 11.0088 4.53127 10.729 4.53127 10.3838C4.53127 10.0387 4.81111 9.75883 5.15627 9.75883H12.8438C13.189 9.75883 13.4688 10.0386 13.4688 10.3838ZM13.4688 14.6494C13.4688 14.9946 13.1889 15.2744 12.8438 15.2744H5.15627C4.81111 15.2744 4.53127 14.9946 4.53127 14.6494C4.53127 14.3043 4.81111 14.0244 5.15627 14.0244H12.8438C13.189 14.0244 13.4688 14.3043 13.4688 14.6494Z" fill="#9E9E9E"/>
                </svg> ${files[0].name}
            `);
        }
    });
</script>
