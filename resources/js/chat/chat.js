import axios from "axios";
import { CRMForm } from "../crm/CRMForm";

const dayNames = [
    'Понедельник',
    'Вторник',
    'Среда',
    'Четверг',
    'Пятница',
    'Суббота',
    'Воскресенье',
];

const monthNames = [
    'Января',
    'Февраля',
    'Марта',
    'Апреля',
    'Мая',
    'Июня',
    'Июля',
    'Августа',
    'Сентября',
    'Октября',
    'Ноября',
    'Декабря',
];

export class Chat {
    chatWindow;
    inputForm;
    textArea;
    messageContainer;
    callForm;
    callForApartments;
    callButton;

    defaultWindowClass;

    clientMessageTemplate;
    managerMessageTemplate;
    agentMessageTemplate;
    dateMessageTemplate;
    funnelMessageTemplate;

    hasManagerMessage = false;
    tooLongNotified = false;
    lastSentTimer;
    crmForm;
    crmApartmentsForm;

    chatCategory = "expert";
    chatTopic;
    hasChatToken;
    token;
    channel = "manager-message";
    //Важно ставить точку перед названием
    channelEvent = ".messaged";
    finalMessage = 'Мы уже подготовили для вас предложение, напишите ваш номер WhatsApp. Оставьте номер телефона к которому привязан мессенджер и эксперт отправит вам всю информацию в ближайшее время';

    sentMessages = [];
    datesInHistory = [];

    questionsFunnel = {
        'Квартиры для инвестиций': {
            'Выберите ценовой диапазон': {
                'до 5 млн. рублей': this.finalMessage,
                'до 10 млн. рублей': this.finalMessage,
                'до 15 млн. рублей': this.finalMessage
            }
        },
        'Квартиры для проживания': {
            'Выберите ценовой диапазон': {
                'до 5 млн. рублей': this.finalMessage,
                'до 10 млн. рублей': this.finalMessage,
                'до 15 млн. рублей': this.finalMessage
            }
        }
    }
    currentFunnel = this.questionsFunnel;
    userSelections = [];

    constructor(chatWindow) {
        this.chatWindow = chatWindow;
        this.defaultWindowClass = this.chatWindow.className;
        this.messageContainer = this.chatWindow.querySelector(".message-container");
        this.chatWindow.querySelector(".chat-window.close").onclick = () => this.hide();
        this.chatWindow.querySelector(".arrow-chevron-right").onclick = () => this.hide();
        this.callForm = document.getElementById("call-from-chat");
        this.callForApartments = document.getElementById("call-for-apartments");
        this.callButton = document.getElementById("chat-quick-call-button");
        this.callForm.querySelector(".chat-window.close").onclick = () => this.callForm.className = this.callForm.className.replace(" shown", "");
        this.inputForm = this.chatWindow.querySelector("form.chat-window.form");
        this.inputForm.onsubmit = (event) => this.onSubmit(event);

        this.callButton.onclick = () => {
            this.callForm.querySelectorAll(".peinag.checkbox").forEach(checkbox => checkbox.checked = true);
            this.crmForm.onSubmit(new Event("submit"));
        }

        this.textArea = this.inputForm.querySelector("textarea");
        this.textArea.addEventListener("keyup", () => {
            this.updateTextArea();
            this.validateInputForm();
        });

        let holdingShift = false;

        this.textArea.addEventListener("keydown", ({ key }) => {
            if (key === "Shift") {
                holdingShift = true;
            }
        });

        this.textArea.addEventListener("keyup", ({ key }) => {
            if (key === "Shift") {
                holdingShift = false;
            }

            if (key === "Enter" && !holdingShift) {
                this.submitMessage(this.inputForm);
            }
        });

        this.textArea.focus();
        this.updateTextArea();
        this.validateInputForm();
        this.loadMessages();
        this.loadFunnel();
    }

    async loadMessages() {
        this.loadMessageTemplates();
        await this.getChatToken();
        await this.loadChatHistory();

        if (!this.datesInHistory.includes("Сегодня")) {
            this.createDateMessage("Сегодня");
        }

        // this.createAgentMessage("Добрый день. Чем мы можем вам помочь?");
    }

    loadCallForm() {
        this.crmForm = new CRMForm(
            "call-from-chat",
            undefined,
            "/api/leave-request",
            ["name", "phone"],
            () => {
                let comment = '\r\nЗапрос на звонок из чата\r\n\r\nСообщения:\r\n' + this.sentMessages.join('\r\n\r\n');

                return comment;
            },
            () => {
                this.createAgentMessage("Ваша заявка успешно отправлена. Ожидайте звонка специалиста.");
                this.callForm.style.display = "none";
                this.callButton.style.display = "none";
            },
            () => {
                this.createAgentMessage("Ошибка при отправке заявки.");
                this.callForm.style.display = "none";
                this.callButton.style.display = "none";
            }
        );
    }

    loadCallForApartmentsForm() {
        this.crmApartmentsForm = new CRMForm(
            "call-for-apartments",
            undefined,
            "/api/leave-request",
            ["name", "phone"],
            () => {
                let comment = '\r\nНаписать в Whatsapp \r\nИнтересует: ' + this.userSelections.join(' ');

                return comment;
            },
            () => {
                this.createAgentMessage("Ваша заявка успешно отправлена.");
                this.callForApartments.style.display = "none";
            },
            () => {
                this.createAgentMessage("Ошибка при отправке заявки.");
                this.callForApartments.style.display = "none";
            }
        );
    }

    containsNumber(str) {
        return !isNaN(parseFloat(str)) && isFinite(str);
    }

    isFormValid() {
        return this.textArea.value.trim().length > 0;
    }

    validateInputForm() {
        this.inputForm.className = this.isFormValid() ? "chat-window form" : "chat-window form invalid";
    }

    loadMessageTemplates() {
        const chatMessageTemplates = Array.from(document.getElementById("chat-message-templates").children);
        this.clientMessageTemplate = chatMessageTemplates[0];
        this.managerMessageTemplate = chatMessageTemplates[1];
        this.agentMessageTemplate = chatMessageTemplates[2];
        this.dateMessageTemplate = chatMessageTemplates[3];
        this.funnelMessageTemplate = chatMessageTemplates[4];
    }

    createClientMessage(text) {
        const message = this.createMessageFromTemplate(this.clientMessageTemplate);
        const messageElement = message.querySelector(".message.text-container").children[0];
        messageElement.textContent = text;

        return message;
    }

    createManagerMessage(name, text, profilePic) {
        const message = this.createMessageFromTemplate(this.managerMessageTemplate);
        const nameAndText = message.querySelector(".message.reciever.name-and-text").children;
        const nameElement = nameAndText[0];
        nameElement.textContent = name;
        const messageElement = nameAndText[1];
        messageElement.textContent = text;
        const profilePicElement = message.querySelector("img");

        if (profilePic != '' && profilePic != null && profilePic != 'null') {
            profilePicElement.src = profilePic;
        } else {
            profilePicElement.style.display = "none";
            const profilePlaceholder = message.querySelector(".message.site-logo-as-icon");
            profilePlaceholder.style.display = "grid";
        }

        return message;
    }

    createAgentMessage(text) {
        const message = this.createMessageFromTemplate(this.agentMessageTemplate);
        const nameAndText = message.querySelector(".message.reciever.name-and-text").children;
        const messageElement = nameAndText[1];
        messageElement.textContent = text;

        return message;
    }

    createDateMessage(text) {
        const message = this.createMessageFromTemplate(this.dateMessageTemplate);
        message.textContent = text;

        return message;
    }

    loadFunnel() {
        const funnel = this.chatWindow.querySelector('.chat-window.funnel');
        const startFunnelKeys = Object.keys(this.questionsFunnel);

        Array.from(funnel.children).forEach((funnelButton, i) => {
            funnelButton.addEventListener("click", () => {
                this.createClientMessage(startFunnelKeys[i]);
                this.userSelections.push(funnelButton.textContent.trim());

                Object.keys(this.questionsFunnel[startFunnelKeys[i]]).forEach(message => {
                    this.createAgentMessage(message);
                    const options = Object.keys(this.questionsFunnel[startFunnelKeys[i]][message]);
                    this.currentFunnel = this.questionsFunnel[startFunnelKeys[i]][message];
                    this.createFunnelStartMessages(options);
                });

                funnel.remove();
            });
        })
    }

    createFunnelStartMessages(texts) {
        const funnelButtonsContainer = this.createMessageFromTemplate(this.funnelMessageTemplate);
        const funnelButton = funnelButtonsContainer.querySelector(".chat-window.category-button");
        const buttons = [];
        const defaultButtonClass = "chat-window category-button";

        texts.forEach(text => {
            const newButton = funnelButton.cloneNode(true);
            newButton.textContent = text;

            newButton.onclick = () => {
                funnelButtonsContainer.style.pointerEvents = "none";
                funnelButtonsContainer.style.opacity = "0.5";
                buttons.forEach(button => button.className = defaultButtonClass);
                newButton.className += " selected";
                this.currentFunnel = this.currentFunnel[newButton.textContent];
                this.userSelections.push(text);

                if (typeof this.currentFunnel === "string") {
                    this.createAgentMessage(this.currentFunnel);
                    this.callForApartments.className += " shown";
                    this.loadCallForApartmentsForm();
                }
            };

            funnelButtonsContainer.appendChild(newButton);
            buttons.push(buttons);
        });

        funnelButton.remove();

        return funnelButtonsContainer;
    }

    createMessageFromTemplate(template) {
        const newMessage = template.cloneNode(true);
        this.messageContainer.appendChild(newMessage);
        this.setTimeOnMessage(newMessage);

        setTimeout(() => {
            this.messageContainer.scrollTo({ left: 0, top: this.messageContainer.scrollHeight, behavior: "smooth" });
        }, 100);

        return newMessage;
    }

    setTimeOnMessage(message, time = null) {
        const timeElement = message.querySelector(".message.time");

        if (!timeElement) {
            return;
        }

        if (time == null) {
            const now = new Date();
            let minutes = now.getMinutes();

            if (minutes < 10) {
                minutes = `0${minutes}`;
            }

            timeElement.textContent = now.getHours() + ":" + minutes;
        } else {
            timeElement.textContent = time;
        }
    }

    updateTextArea() {
        this.textArea.style.height = "1px";
        this.textArea.style.height = (this.textArea.scrollHeight) + "px";
        this.textArea.style.borderRadius = this.textArea.scrollHeight < 30 ? "24px" : "16px";
    }

    async getChatToken() {
        const response = await axios.get("/api/chat-token");

        if (response.status == 200) {
            this.hasChatToken = response.data.hasToken;
            this.token = response.data.token;

            window.Echo.channel(`${this.channel}.${this.token}`).listen(this.channelEvent, (e) => this.onMessageRecieved(e));
        } else {
            await this.getChatToken();
        }
    }

    async loadChatHistory() {
        const response = await axios.get("/api/chat-history");
        let lastDate;

        response.data.history.forEach(sessionMessage => {
            let message;
            const date = new Date(sessionMessage.created_at);
            const now = new Date();

            if (!lastDate || lastDate.getDate() != date.getDate()) {
                lastDate = date;
                const dayNumber = lastDate.getDay();
                const day = lastDate.getDate();
                const month = lastDate.getMonth()
                const year = lastDate.getFullYear().toString();//.substring(2);
                let displayDate = `${dayNames[dayNumber]} ${day} ${monthNames[month]} ${year}`;
                let dateDifference = now.getDate() - lastDate.getDate();

                //TODO: не писать год если он текущий
                //TODO: Писать день недели только за последние две недели
                //TODO: скрывать просьбу оставить заявку если менеджер вдруг написал

                switch (dateDifference) {
                    case 0:
                        displayDate = 'Сегодня';
                        break;
                    case 1:
                        displayDate = 'Вчера';
                        break;
                    case 2:
                        displayDate = 'Позавчера';
                        break;
                    default:
                        break;
                }

                this.datesInHistory.push(displayDate);
                //TODO: hide the message if actual message did not appear
                this.createDateMessage(displayDate);
            }

            let minutes = date.getMinutes();

            if (minutes < 10) {
                minutes = `0${minutes}`;
            }

            const time = date.getHours() + ":" + minutes;

            if (sessionMessage.author == "user") {
                message = this.createClientMessage(sessionMessage.message);
            }

            if (sessionMessage.author == "manager") {
                message = this.createManagerMessage(sessionMessage.authorName, sessionMessage.message, sessionMessage.profilePic);
            }

            if (message) {
                this.setTimeOnMessage(message, time);
            }
            //Возможно делать что-то еще с другими авторами
        });
    }

    isLastMessageByManager() {
        this.messageContainer.lastChild.className.includes("reciever");
    }

    setCategoryFromButton(button) {
        this.chatCategory = button.id;
    }

    onMessageRecieved(message) {
        this.hasManagerMessage = true;
        this.createManagerMessage(message.managerName, message.message, message.managerProfilePic);
    }

    async submitMessage(form) {
        if (!this.isFormValid()) {
            return;
        }

        const formData = new FormData(form);
        formData.set("chatCategory", this.chatCategory);
        const message = this.createClientMessage(formData.get("message"));
        message.style.opacity = 0.5;
        message.style.pointerEvents = "none";
        this.sentMessages.push(this.textArea.value);
        this.textArea.value = "";
        this.validateInputForm();
        this.updateTextArea();
        await axios.post("/api/send-chat-message", formData);

        if (this.lastSentTimer) {
            clearTimeout(this.lastSentTimer);
        }

        this.lastSentTimer = setTimeout(() => {
            this.weWillCallLater();
        }, 60 * 1 * 1000);

        message.style.opacity = 1;
        message.style.pointerEvents = "initial";

        const now = new Date();

        if ((now.getHours() < 10 || now.getHours() >= 21)) {
            let callMessage = "Наши операторы работают с 10:00 до 21:00. Оставьте свои контактные данные и с вами обязательно свяжутся.";

            if (isUserAuthorized) {
                callMessage = "Наши операторы работают с 10:00 до 21:00. Оставьте свои контактные данные и с вами обязательно свяжутся.";
            }

            this.weWillCallLater(callMessage);
        }
    }

    weWillCallLater(message = null) {
        if (this.tooLongNotified || this.isLastMessageByManager() || this.hasManagerMessage) {
            return;
        }

        this.tooLongNotified = true;

        if (isUserAuthorized) {
            this.callButton.style.display = "grid";
        } else {
            this.callForm.className += " shown";
        }

        if (message == null) {
            if (isUserAuthorized) {
                this.createAgentMessage("Сейчас все специалисты заняты. Нажмите кнопку «Перезвоните мне» и наш специалист перезвонит вам в ближайшее время.");
            } else {
                this.createAgentMessage("Сейчас все специалисты заняты. Заполните форму заявки и наш специалист перезвонит вам в ближайшее время.");
            }
        } else {
            this.createAgentMessage(message);
        }

        this.loadCallForm();
    }

    async onSubmit(event) {
        event.preventDefault();

        if (!this.hasChatToken) {
            return;
        }

        await this.submitMessage(event.target);
    }

    switch() {
        if (this.chatWindow.className == this.defaultWindowClass) {
            this.show();
        } else {
            this.hide();
        }
    }

    show() {
        if (!this.chatWindow.className.includes(" shown")) {
            this.chatWindow.className += " shown";
        }
    }

    hide() {
        this.chatWindow.className = this.defaultWindowClass;

        sessionStorage.setItem('chatWasClosed', true);
    }
}