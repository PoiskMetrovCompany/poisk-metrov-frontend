<div class="mt-3">
    <div class="alert alert-outline">
        <form id="feedUploadForm" action="{{ route('admin.form.feed.synchronization')  }}" method="POST" enctype="multipart/form-data" class="mx-auto">
            @csrf
            <input type="text" name="city" id="cityInput" value="Санкт-Питербург" class="d-none">
            <input type="file" name="file" id="fileInput" class="d-none" accept=".zip,.rar,.7z,.tar,.gz,.bz2">

            <button type="button" id="uploadButton" class="btn btn-active">Загрузить данные</button>
            <div class="mt-2">
                <span>Допускается загрузка архивов</span>
            </div>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script type="module">
            document.getElementById('uploadButton').addEventListener('click', function () {
                document.getElementById('fileInput').click();
            });

            document.getElementById('fileInput').addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('city', document.getElementById('cityInput').value);
                formData.append('_token', '{{ csrf_token() }}');

                axios.post(document.getElementById('feedUploadForm').action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                    .then(response => {
                        console.log('Файл успешно загружен:', response.data);
                    })
                    .catch(error => {
                        console.error('Ошибка при загрузке:', error.response?.data || error.message);
                    });
            });
        </script>
    </div>
</div>
