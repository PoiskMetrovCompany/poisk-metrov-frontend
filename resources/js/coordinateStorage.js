import { updateMap } from "./buildingCard/map.js";

let buttons = document.querySelectorAll('.save-coordinates-btn')
buttons.forEach(button => {
    button.addEventListener('click', async function () {
        const longitude = parseFloat(button.getAttribute('data-longitude'));
        const latitude = parseFloat(button.getAttribute('data-latitude'));

        saveCoordinates(longitude, latitude);

        try {
            await updateMap();
        } catch (error) {
            console.error('Error updating map:', error);
        }
    });
});

function saveCoordinates(longitude, latitude) {
    if (longitude && latitude) {
        localStorage.setItem('longitude', longitude);
        localStorage.setItem('latitude', latitude);

        const savedLongitude = localStorage.getItem('longitude');
        const savedLatitude = localStorage.getItem('latitude');
        console.log('Coordinates saved:', savedLongitude, savedLatitude);
    }
}
