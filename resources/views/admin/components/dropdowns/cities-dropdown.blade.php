<div class="dropdown mb-4">
    <button class="col-5 btn dropdown-toggle dropdown-custom d-flex align-items-center justify-content-between" type="button" id="cityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <span>Город: <span class="highlight" id="selectedCity">Новосибирск</span></span>
        <span class="dropdown-icon">
            <svg width="16" height="21" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 10.9C9.44975 10.9 10.625 9.69117 10.625 8.2C10.625 6.70883 9.44975 5.5 8 5.5C6.55025 5.5 5.375 6.70883 5.375 8.2C5.375 9.69117 6.55025 10.9 8 10.9Z" stroke="#EC7D3F" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M8 19C11.5 15.4 15 12.1764 15 8.2C15 4.22355 11.866 1 8 1C4.13401 1 1 4.22355 1 8.2C1 12.1764 4.5 15.4 8 19Z" stroke="#EC7D3F" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6L8 10L12 6" stroke="#494949" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    </button>
    <ul class="col-5 dropdown-menu">
        <li><a class="dropdown-item active" href="#" onclick="selectCity('Новосибирск')">Новосибирск</a></li>
        <li><a class="dropdown-item" href="#" onclick="selectCity('Москва и МО')">Москва и МО</a></li>
        <li><a class="dropdown-item" href="#" onclick="selectCity('Санкт-Петербург')">Санкт-Петербург</a></li>
    </ul>
</div>

<script>
    function selectCity(city) {
        document.getElementById('selectedCity').textContent = city;

        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('active');
        });

        event.target.classList.add('active');

        localStorage.setItem('selectedCity', city);
    }
</script>
