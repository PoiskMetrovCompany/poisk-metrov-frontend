import { ApartmentFilters } from "./ApartmentFilters";
import { LoadingDots } from "../catalogueFilters/LoadingDots";

document.addEventListener("DOMContentLoaded", () => {
    const apartmentFilters = new ApartmentFilters();

    const searchStartEventName = "searchStarted";
    const searchEndEventName = "searchEnded";

    const loadingDots = new LoadingDots(searchStartEventName, searchEndEventName, "apartments-loader-dots");
});