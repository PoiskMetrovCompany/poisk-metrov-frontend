import { Paginator } from "../paginators/paginator";
import { TabsGrid } from "../TabsGrid";

document.addEventListener("DOMContentLoaded", () => {
    const buttonsContainer1 = document.getElementById("employees-switch-buttons-1");
    const buttonsContainer2 = document.getElementById("employees-switch-buttons-2");
    const buttonsContainer1Small = document.getElementById("employees-switch-buttons-1-small");
    const buttonsContainer2Small = document.getElementById("employees-switch-buttons-2-small");
    const office1 = document.getElementById('office1');
    const office2 = document.getElementById('office2');
    const showAll1 = document.getElementById('show-all-1');
    const showAll2 = document.getElementById('show-all-2');
    const office1All = document.getElementById('office1-all');
    const office2All = document.getElementById('office2-all');
    const office1Small = document.getElementById('office1-small');
    const office2Small = document.getElementById('office2-small');
    const showAll1Small = document.getElementById('show-all-1-small');
    const showAll2Small = document.getElementById('show-all-2-small');
    const office1AllSmall = document.getElementById('office1-all-small');
    const office2AllSmall = document.getElementById('office2-all-small');
    const width = window.innerWidth;

    if (width >= 1440) {
        let employeesTabs = new TabsGrid("employees-tabs", (tab) => {
            switch (tab.textContent) {
                case "Офис на ул. Кошурникова": {
                    office1.style.display = 'grid';
                    office2.style.display = 'none';
                    office1All.style.display = 'none';
                    office2All.style.display = 'none';
                    break;
                }
                case "Офис на ул. Дуси Ковальчук": {
                    office1.style.display = 'none';
                    office2.style.display = 'grid';
                    office1All.style.display = 'none';
                    office2All.style.display = 'none';
                }
            }
        });

        employeesTabs.disableClass = "";
        employeesTabs.buttons.children[0].dispatchEvent(new CustomEvent("click"));

        if (showAll1) {
            showAll1.addEventListener("click", () => {
                office1.style.display = 'none';
                office1All.style.display = 'grid';
            });
        }

        if (showAll2) {
            showAll2.addEventListener("click", () => {
                office2.style.display = 'none';
                office2All.style.display = 'grid';
            });
        }

        if (buttonsContainer1) {
            const paginator = new Paginator();
            paginator.pageClass = "company our-workers workers";
            paginator.buttonsGridId = buttonsContainer1.id;
            paginator.pagesContainerId = "office1";
            paginator.load();
            paginator.hideButtons = false;
        }

        if (buttonsContainer2) {
            const paginator = new Paginator();
            paginator.pageClass = "company our-workers workers";
            paginator.buttonsGridId = buttonsContainer2.id;
            paginator.pagesContainerId = "office2";
            paginator.load();
            paginator.hideButtons = false;
        }
    } else {
        let employeesTabs = new TabsGrid("employees-tabs", (tab) => {
            switch (tab.textContent) {
                case "Офис на ул. Кошурникова": {
                    office1Small.style.display = 'grid';
                    office2Small.style.display = 'none';
                    office1AllSmall.style.display = 'none';
                    office2AllSmall.style.display = 'none';
                    break;
                }
                case "Офис на ул. Дуси Ковальчук": {
                    office1Small.style.display = 'none';
                    office2Small.style.display = 'grid';
                    office1AllSmall.style.display = 'none';
                    office2AllSmall.style.display = 'none';
                }
            }
        });

        employeesTabs.disableClass = "";
        employeesTabs.buttons.children[0].dispatchEvent(new CustomEvent("click"));

        if (showAll1Small) {
            showAll1Small.addEventListener("click", () => {
                office1Small.style.display = 'none';
                office1AllSmall.style.display = 'grid';
            });
        }

        if (showAll2Small) {
            showAll2Small.addEventListener("click", () => {
                office2Small.style.display = 'none';
                office2AllSmall.style.display = 'grid';
            });
        }

        if (buttonsContainer1Small) {
            const paginator = new Paginator();
            paginator.pageClass = "company our-workers workers";
            paginator.buttonsGridId = buttonsContainer1Small.id;
            paginator.pagesContainerId = "office1-small";
            paginator.load();
            paginator.hideButtons = false;
        }

        if (buttonsContainer2Small) {
            const paginator = new Paginator();
            paginator.pageClass = "company our-workers workers";
            paginator.buttonsGridId = buttonsContainer2Small.id;
            paginator.pagesContainerId = "office2-small";
            paginator.load();
            paginator.hideButtons = false;
        }
    }
});