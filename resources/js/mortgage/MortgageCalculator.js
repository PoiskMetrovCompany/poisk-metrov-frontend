import axios from "axios";
import { TabsGrid } from "../TabsGrid";
import { floor10 } from "../decimalAdjust";
import { NonSearchDropdownFilter } from "../search/dropdownFilters/NonSearchDropdownFilter";
import { Slider } from "./Slider";
import { Mortgage } from "./Mortgage";

export class MortgageCalculator {
    allPrograms = "Все программы";
    currentMortgage;
    priceSlider;
    feeSlider;
    yearsSlider;
    selectedPrograms = [this.allPrograms];
    selectedBanks = [];
    tabsGrid;
    banksDropdown;
    cachedFeeRatio = 0;
    preferredPrice = 0;
    preferredFeeRatio = 0;
    preferredYear = 0;
    sortingParameter = 'min_rate';
    sortingOrder = 'asc';
    currentMortgageId = -1;
    allCards = [];

    constructor() {
        this.tabsGrid = new TabsGrid('mortgage-type-selection-buttons', (button) => this.onTabClicked(button), true);
        this.banksDropdown = new NonSearchDropdownFilter("bank");
        this.banksDropdown.onOptionSelected = (event) => {
            if (!this.banksDropdown.allowMultiple) {
                this.selectedBanks = [];
            }

            if (!this.selectedBanks.includes(event.searchid)) {
                this.selectedBanks.push(event.searchid);
            }

            this.updateMortgageDropdowns();
        }

        this.banksDropdown.onOptionDeselected = (event) => {
            if (!this.selectedBanks.includes(event.searchid)) {
                return;
            }

            this.selectedBanks = this.selectedBanks.filter(bank => bank != event.searchid);

            this.updateMortgageDropdowns();
        }

        this.priceSlider = new Slider("mortgage-price-slider", "current-mortgage-price", "max-mortgage-price", "currentPrice", 100000,
            () => this.onPriceSliderInput(),
            () => this.onBeforePriceUpdate());
        this.priceSlider.getMaxDisplayInfo = () => this.priceSlider.addSpaces(this.priceSlider.slider.max);

        this.feeSlider = new Slider("mortgage-start-fee-slider", "current-fee", "max-start-fee", "currentFee", 1000, () => this.onFeeSliderInput());
        this.feeSlider.getDisplayInfo = (withSpaces = false) => {
            if (withSpaces) {
                return this.feeSlider.addSpaces(Number.parseInt(this.currentMortgage.currentFee));
            } else {
                return Number.parseInt(this.currentMortgage.currentFee);
            }
        };
        this.feeSlider.getMaxDisplayInfo = () => this.getFeeRatio() + " %";

        this.yearsSlider = new Slider("mortgage-year-slider", "current-mortgage-term", "max-mortage-term", "currentYears", 1, () => this.onYearSliderInput());

        this.loadMortgageProgramDropdowns();

        this.currentMortgage = new Mortgage();
        this.currentMortgage.startFee = 20;
        this.currentMortgage.maxFee = 30;
        this.currentMortgage.baseRate = 3;
        this.currentMortgage.maxRate = 9;
        this.currentMortgage.minTerm = 1
        this.currentMortgage.maxTerm = 30
        this.currentMortgage.minSumm = 1000000;
        this.currentMortgage.maxSumm = 10000000;

        if (typeof maxMortgageParameters !== 'undefined') {
            this.currentMortgage.minTerm = maxMortgageParameters.from_year;
            this.currentMortgage.minSumm = maxMortgageParameters.from_amount;
            this.currentMortgage.baseRate = maxMortgageParameters.min_rate;
            this.currentMortgage.startFee = maxMortgageParameters.min_initial_fee;
            this.currentMortgage.maxTerm = maxMortgageParameters.to_year;
            this.currentMortgage.maxSumm = maxMortgageParameters.to_amount;
            this.currentMortgage.maxRate = maxMortgageParameters.max_rate;
            this.currentMortgage.maxFee = maxMortgageParameters.max_initial_fee;
        }

        this.currentMortgage.currentPrice = this.currentMortgage.minSumm;

        if (typeof planData !== 'undefined') {
            this.currentMortgage.currentPrice = Math.min(Math.max(this.currentMortgage.minSumm, planData.price), this.currentMortgage.maxSumm);
        }

        [this.priceSlider.slider, this.feeSlider.slider, this.yearsSlider.slider].forEach(slider => {
            slider.addEventListener("mouseup", () => {
                this.updateMortgageDropdowns();
            });

            slider.addEventListener("touchend", () => {
                this.updateMortgageDropdowns();
            });
        });

        this.loadSliders(this.currentMortgage, true);
        this.loadSortingDropdown();
        this.updateCreditSumm(this.currentMortgage);
    }

    loadMortgageProgramDropdowns() {
        const mortgageProgramsSortedByRates = Array.from(document.getElementsByClassName("mortgage-programs dropdown-card primary-card"));;
        this.allCards = [];

        mortgageProgramsSortedByRates.forEach(mortgageProgramDropdown => {
            let timeout;
            const cards = Array.from(mortgageProgramDropdown.parentElement.getElementsByClassName("offer-card base-container"));
            this.allCards.push(...cards);

            cards.forEach(card => {
                card.onclick = () => {
                    this.currentMortgage.currentRate = Number.parseFloat(card.getAttribute("minRate"));
                    this.currentMortgage.currentPrice = Math.min(Math.max(this.currentMortgage.minSumm, this.priceSlider.slider.value), this.currentMortgage.maxSumm);
                    this.currentMortgageId = card.getAttribute("mortgageId");

                    this.calculateCurrentMortgage();
                    document.getElementById("choose-mortgage-program-hint").style.display = "none";
                    this.updateCardActivityVisuals();
                }
            });

            //Много карточек тормозит сайт, но если они скрываются через стили, то плохо работает анимация закрытия, поэтому скрываем обратно через таймаут
            mortgageProgramDropdown.onclick = () => {
                if (timeout) {
                    clearTimeout(timeout);
                }

                if (mortgageProgramDropdown.parentElement.className.includes(" open")) {
                    mortgageProgramDropdown.parentElement.className = mortgageProgramDropdown.parentElement.className.replace(" open", "");

                    timeout = setTimeout(() => {
                        cards.forEach(card => {
                            card.style.display = "none";
                        });

                        timeout = undefined;
                    }, 350);
                } else {
                    mortgageProgramDropdown.parentElement.className += " open";

                    cards.forEach(card => {
                        card.style.display = "grid";
                    });
                }
            }
        });

        this.updateCardActivityVisuals();
    }

    updateCardActivityVisuals() {
        let activateParentElementFor;

        this.allCards.forEach(card => {
            if (card.getAttribute("mortgageId") == this.currentMortgageId) {
                if (!card.className.includes(" active")) {
                    card.className += " active";
                }

                activateParentElementFor = card;
            } else {
                card.className = card.className.replace(" active", "");
                card.parentElement.className = card.parentElement.className.replace(" active", "");
            }
        });

        if (activateParentElementFor && !activateParentElementFor.parentElement.className.includes(" active")) {
            activateParentElementFor.parentElement.className += " active";
        }
    }

    onPriceSliderInput() {
        this.feeSlider.load(this.currentMortgage,
            this.currentMortgage.currentPrice * (this.currentMortgage.startFee / 100),
            this.feeSlider.slider.value,
            this.currentMortgage.currentPrice * (this.currentMortgage.maxFee / 100));
        this.preferredPrice = this.priceSlider.slider.value;
        this.preferredFeeRatio = this.getFeeRatio();
        this.calculateCurrentMortgage()
    }

    onFeeSliderInput() {
        this.preferredFeeRatio = this.getFeeRatio();
        this.calculateCurrentMortgage();
    }

    onYearSliderInput() {
        this.preferredYear = this.yearsSlider.slider.value;
        this.calculateCurrentMortgage();
    }

    onBeforePriceUpdate() {
        this.cachedFeeRatio = this.currentMortgage[this.feeSlider.propertyDisplay] / this.currentMortgage[this.priceSlider.propertyDisplay];
    }

    onTabClicked(button) {
        const data = button.getAttribute("data-name");
        const allButtons = Array.from(this.tabsGrid.buttons.children);

        if (this.selectedPrograms.includes(data)) {
            this.selectedPrograms = this.selectedPrograms.filter(program => program != data);
        } else {
            if (data == this.allPrograms) {
                this.selectedPrograms = [];
            } else {
                this.selectedPrograms = this.selectedPrograms.filter(program => program != this.allPrograms);
            }

            this.selectedPrograms.push(data);
        }

        if (this.selectedPrograms.length == 0) {
            this.selectedPrograms.push(this.allPrograms);
        }

        allButtons.forEach(button => {
            const data = button.getAttribute("data-name");

            if (!this.selectedPrograms.includes(data)) {
                button.className = this.tabsGrid.buttonClass + " " + this.tabsGrid.disableClass;
            } else {
                button.className = this.tabsGrid.buttonClass + " " + this.tabsGrid.enableClass;
            }
        });

        this.updateMortgageDropdowns();
    }

    loadSortingDropdown() {
        const sortingDropdown = document.getElementById("mortgages-sorting-dropdown");

        if (!sortingDropdown) {
            return;
        }

        const placeHolder = sortingDropdown.querySelector(".placeholder");
        const dropdown = sortingDropdown.querySelector(".custom-dropdown.base-container");
        const items = Array.from(sortingDropdown.querySelectorAll(".names-dropdown.item"));
        const defaultDropdownClass = dropdown.className;

        if (!sortingDropdown) {
            return;
        }

        sortingDropdown.addEventListener("click", (event) => {
            if (event.target != dropdown) {
                if (!dropdown.className.includes("open")) {
                    dropdown.className = defaultDropdownClass + " open";
                } else {
                    dropdown.className = defaultDropdownClass;
                }
            }
        });

        sortingDropdown.addEventListener("focusout", (event) => {
            dropdown.className = defaultDropdownClass;
        });

        const sortingOptions = [
            {
                parameter: 'min_rate',
                order: 'asc'
            },
            {
                parameter: 'max_rate',
                order: 'desc'
            },
            {
                parameter: 'monthly_payment',
                order: 'asc'
            },
            {
                parameter: 'monthly_payment',
                order: 'desc'
            }
        ];

        items.forEach((item, i) => {
            item.addEventListener("click", async () => {
                placeHolder.textContent = item.textContent;
                this.sortingParameter = sortingOptions[i].parameter;
                this.sortingOrder = sortingOptions[i].order;
                this.updateMortgageDropdowns();
            });
        });
    }

    async updateMortgageDropdowns() {
        const query = new URLSearchParams();

        this.selectedPrograms.forEach(program => {
            if (program != this.allPrograms) {
                query.append('categories[]', program);
            }
        });

        this.selectedBanks.forEach(bank => {
            query.append('banks[]', bank);
        });

        if (this.preferredPrice > 0) {
            query.set('preferred_price', this.preferredPrice);
        }

        if (this.preferredFeeRatio > 0) {
            query.set('preferred_initial_fee', this.preferredFeeRatio);
        }

        if (this.preferredYear >= 1) {
            query.set('preferred_year', this.preferredYear);
        }

        query.set('sorting_parameter', this.sortingParameter);
        query.set('sorting_direction', this.sortingOrder);
        const mortgageDropdownContainer = document.querySelector(".mortgage-programs.base-container");
        mortgageDropdownContainer.style.opacity = 0.5;
        mortgageDropdownContainer.style.pointerEvents = "none";
        const response = await axios.get('/filtered-mortgages?' + query.toString());
        mortgageDropdownContainer.style.opacity = 1;
        mortgageDropdownContainer.style.pointerEvents = "all";
        const resultsCounter = document.querySelector(".mortgage-programs.results-counter").querySelector("span");

        //child.remove() удаляет не все
        mortgageDropdownContainer.innerHTML = '';
        let viewCount = 0;

        response.data.views.forEach(view => {
            mortgageDropdownContainer.innerHTML += view;
            viewCount += Number.parseInt(mortgageDropdownContainer.lastElementChild.getAttribute("offerscount"));
        });

        if (viewCount > 0) {
            if (viewCount < maxMortgages) {
                resultsCounter.textContent = `${viewCount} предложений из ${maxMortgages}`;
            } else {
                resultsCounter.textContent = `${viewCount} предложений`;
            }
        } else {
            resultsCounter.textContent = `Ни одна из программ не подходит под эти условия`;
        }

        this.loadMortgageProgramDropdowns();
    }

    getFeeRatio() {
        return floor10((this.currentMortgage[this.feeSlider.propertyDisplay] / this.currentMortgage[this.priceSlider.propertyDisplay]) * 100, -1);
    }

    calculateCurrentMortgage() {
        this.calculateMortgage(this.currentMortgage);
    }

    loadSliders(mortgage, skipCurrentRate = false) {
        let currentPrice = mortgage.currentPrice;
        let currentFee = 20; //mortgage.baseRate; //(currentPrice * (mortgage.startFee / 100) + currentPrice * (mortgage.maxFee / 100)) / 2;
        let currentYear = 10; //mortgage.minTerm; //(mortgage.maxTerm + mortgage.minTerm) / 2;

        mortgage.currentPrice = currentPrice;
        mortgage.currentFee = currentFee;
        mortgage.currentYears = currentYear;

        if (!skipCurrentRate) {
            mortgage.currentRate = this.currentMortgage.baseRate;
        }

        //https://www.rncb.ru/media/article/pervonachalnyj-vznos-po-ipoteke-chto-nuzhno-znat-zayomshiku/
        this.priceSlider.load(mortgage, mortgage.minSumm, currentPrice, mortgage.maxSumm);
        this.feeSlider.load(mortgage, currentPrice * (mortgage.startFee / 100), currentFee, currentPrice * (mortgage.maxFee / 100));
        this.yearsSlider.load(mortgage, mortgage.minTerm, currentYear, mortgage.maxTerm);
        this.calculateCurrentMortgage();

        this.preferredPrice = mortgage.currentPrice;
        this.preferredYear = mortgage.currentYears;
        this.preferredFeeRatio = this.getFeeRatio();
    }

    formatMoney(money) {
        return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', minimumFractionDigits: 0 }).format(money);
    }

    updateCreditSumm(mortgage) {
        const mortgageDisplay = document.getElementById("mortgage-display");
        let S = mortgage.currentPrice - mortgage.currentFee;
        mortgageDisplay.textContent = this.priceSlider.addSpaces(S.toString()) + " ₽";
    }

    calculateMortgage(mortgage) {
        this.updateCreditSumm(this.currentMortgage);

        if (mortgage.currentRate == 0) {
            return;
        }

        const resultDisplay = document.getElementById("monthly-payment");
        const requiredIncome = document.getElementById("required-income");

        let G = mortgage.currentRate / 12 / 100;
        let T = mortgage.currentYears * 12;
        // let coeff = G / (1 - Math.pow(1 + G, -(T - 1)));
        let S = mortgage.currentPrice - mortgage.currentFee;
        //TODO: неплохо бы иметь более подробный ежемесячный платеж как на https://calcus.ru/kalkulyator-ipoteki
        //расчетная формула берется так же с него и отличается от https://www.vtb.ru/articles/kak-rasschityvaetsya-ipoteka/
        //по которой расчеты по всей видимости были раньше
        // let PM = S * coeff;
        let PM = floor10((S / T) + (S * G), 0);

        resultDisplay.textContent = this.formatMoney(Number.parseInt(PM.toString()));
        //Считается что необходимый доход должен быть минимум в 2 раза больше ежемесячного платежа
        requiredIncome.textContent = this.formatMoney(Number.parseInt((PM * 2).toString()));
    }

}