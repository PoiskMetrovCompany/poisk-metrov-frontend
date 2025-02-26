import { CatalogueFilters } from "./CatalogueFilters";
import { CardLoader } from "./CardLoader";
import { NothingFound } from "./NothingFound";
import { LoadingDots } from "./LoadingDots";
import { FoundCount } from "./FoundCount";
import { CataloguePaginator } from "./CataloguePagiantor";
import { MapLoader } from "./MapLoader";

document.addEventListener("DOMContentLoaded", () => {
    const catalogueFilters = new CatalogueFilters();

    const searchStartEventName = "searchStarted";
    const searchEndEventName = "searchEnded";

    const cardLoader = new CardLoader(searchStartEventName, searchEndEventName);
    const nothingFound = new NothingFound(searchStartEventName, searchEndEventName);
    // const loadingDots = new LoadingDots(searchStartEventName, searchEndEventName, "building-loader-dots");
    const foundCount = new FoundCount(searchStartEventName, searchEndEventName);
    const paginator = new CataloguePaginator(searchStartEventName, searchEndEventName, catalogueFilters);
    const mapLoader = new MapLoader(searchStartEventName, searchEndEventName);
});