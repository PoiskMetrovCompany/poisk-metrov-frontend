import { ApartmentReservations } from "./apartmentReservations";
import { ClientTable } from "./clientTable";
import { EstateCategories } from "./estateCategories";
import { SalesAnnouncements } from "./salesAnnouncements";

window.customElements.define('client-table', ClientTable);
window.customElements.define('sales-announcements', SalesAnnouncements);
window.customElements.define('apartment-reservations', ApartmentReservations);
window.customElements.define('estate-categories', EstateCategories);