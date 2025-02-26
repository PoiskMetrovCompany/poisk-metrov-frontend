import { getCookie } from "../cookies";

export const cityCenterCooridnates = {
    "novosibirsk": [83, 55],
    "st-petersburg": [30.5, 60],
    "black-sea": [39.8, 43.5],
    "crimea": [34.4, 45],
    "moscow": [37.7, 55.8],
    "chelyabinsk": [61.45, 55.15],
    "ekaterinburg": [60.7, 56.85],
    "kaliningrad": [20.5, 54.7],
    "voronezh": [39.2, 51.65],
    "far-east": [132, 43.25],
    "krasnodar": [39, 45],
    "thailand": [98.5, 7.95],
    "ufa": [56, 54.7],
    "kazan": [49.1, 55.7],
};
export const cityCodes = [
    "novosibirsk",
    "st-petersburg",
    "novosibirsk",
    "black-sea",
    "crimea",
    "moscow",
    "chelyabinsk",
    "ekaterinburg",
    "kaliningrad",
    "voronezh",
    "far-east",
    "krasnodar",
    "thailand",
    "ufa",
    "kazan"
];

export function getCity() {
    const city = getCookie("selectedCity");

    if (city) {
        const cityName = city.toLowerCase().replace(" ", "-");
        if (cityCodes.includes(cityName))
            return cityName;
    }

    return "st-petersburg";
}

if (typeof currentCityCached !== 'undefined') {
    currentCityCached = getCity();
}
