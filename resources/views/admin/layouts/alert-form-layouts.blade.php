<div class="mt-3">
    <div class="alert alert-outline">
        <form id="feedUploadForm" action="{{ route('admin.form.feed.synchronization')  }}" method="POST" enctype="multipart/form-data" class="mx-auto">
            @csrf
            <input type="file" name="file" id="fileInput" class="d-none">

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

                formData.append('_token', '{{ csrf_token() }}');

                axios.post(document.getElementById('feedUploadForm').getAttribute('action'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.error(error.response?.data || error);
                    });
            });
        </script>
    </div>
</div>
