function g(s){return(g=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(o){return typeof o}:function(o){return o&&typeof Symbol=="function"&&o.constructor===Symbol&&o!==Symbol.prototype?"symbol":typeof o})(s)}function A(s,o){if(!(s instanceof o))throw new TypeError("Cannot call a class as a function")}function b(s,o){for(var e=0;e<o.length;e++){var t=o[e];t.enumerable=t.enumerable||!1,t.configurable=!0,"value"in t&&(t.writable=!0),Object.defineProperty(s,t.key,t)}}function E(s,o,e){return o&&b(s.prototype,o),e&&b(s,e),Object.defineProperty(s,"prototype",{writable:!1}),s}function _(s,o){if(typeof o!="function"&&o!==null)throw new TypeError("Super expression must either be null or a function");s.prototype=Object.create(o&&o.prototype,{constructor:{value:s,writable:!0,configurable:!0}}),Object.defineProperty(s,"prototype",{writable:!1}),o&&h(s,o)}function R(s){var o=v();return function(){var e,t=p(s);return S(this,o?(e=p(this).constructor,Reflect.construct(t,arguments,e)):t.apply(this,arguments))}}function S(s,o){if(o&&(g(o)==="object"||typeof o=="function"))return o;if(o!==void 0)throw new TypeError("Derived constructors may only return object or undefined");return L(s)}function L(s){if(s===void 0)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return s}function f(s){var o=typeof Map=="function"?new Map:void 0;return(f=function(e){if(e===null||!C(e))return e;if(typeof e!="function")throw new TypeError("Super expression must either be null or a function");if(o!==void 0){if(o.has(e))return o.get(e);o.set(e,t)}function t(){return m(e,arguments,p(this).constructor)}return t.prototype=Object.create(e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),h(t,e)})(s)}function m(s,o,e){return(m=v()?Reflect.construct:function(t,n,r){var a=[null];return a.push.apply(a,n),a=new(Function.bind.apply(t,a)),r&&h(a,r.prototype),a}).apply(null,arguments)}function v(){if(typeof Reflect>"u"||!Reflect.construct||Reflect.construct.sham)return!1;if(typeof Proxy=="function")return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],function(){})),!0}catch{return!1}}function C(s){return Function.toString.call(s).indexOf("[native code]")!==-1}function h(s,o){return(h=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(s,o)}function p(s){return(p=Object.setPrototypeOf?Object.getPrototypeOf:function(o){return o.__proto__||Object.getPrototypeOf(o)})(s)}var l=Object.freeze({sliderBackgroundColor:"tomato",sliderBorderColor:"#8b8b8b",sliderBorderRadius:"4px",sliderCircleSize:20,sliderCircleBackgroundColor:"#ffffff",sliderCircleFocusColor:"#0074cc",sliderCommonSize:"0.5em"}),i=Object.freeze({MIN:"min",MAX:"max",SLIDER_ID:"minMaxSlider",MIN_LABEL_ID:"minLabel",MAX_LABEL_ID:"maxLabel",RANGE_STOPPED_EVENTS:["mouseup","touchend","keyup"],CUSTOM_EVENT_TO_EMIT_NAME:"range-changed",RANGE_INPUT_DATA_LABEL_MIN:"data-range-input-label-min",RANGE_INPUT_DATA_LABEL_MAX:"data-range-input-label-max"}),y=document.createElement("template");y.innerHTML=`
    <style>
      .min-max-slider { 
        position: relative;
        width: 100%;
        text-align: center;
      }
         
      .min-max-slider > label {
        position: absolute;
        left: -10000px;
        top: auto;
        width: 1px;
        height: 1px;
        overflow: hidden;
      }
        
      .min-max-slider > .legend {
        display: flex;
        justify-content: space-between;
      }
        
      .min-max-slider > .range-input {
        --sliderCircleSize: `.concat(l.sliderCircleSize,`px;
        --sliderColor: `).concat(l.sliderCircleBackgroundColor,`;
        --sliderBorderColor: `).concat(l.sliderBorderColor,`;
        --sliderFocusBorderColor: `).concat(l.sliderCircleFocusColor,`;
        --sliderCircleBorder: 1px solid var(--sliderBorderColor);
        --sliderCircleFocusBorder: 2px solid var(--sliderFocusBorderColor);
        cursor: pointer;
        position: absolute;
        -webkit-appearance: none;
        appearance: none;
        outline: none !important;
        background: transparent;
        background-image: linear-gradient(to bottom, transparent 0%, transparent 30%, `).concat(l.sliderBackgroundColor," 30%, ").concat(l.sliderBackgroundColor,` 60%, transparent 60%, transparent 100%); height: 6px;
      }
        
      .min-max-slider > .range-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: var(--sliderCircleSize);
        height: var(--sliderCircleSize);
        background-color: var(--sliderColor);
        cursor: pointer;
        border: var(--sliderCircleBorder);
        border-radius: 100%;
      }
      
      .min-max-slider > .range-input::-moz-range-thumb {
        width: var(--sliderCircleSize);
        height: var(--sliderCircleSize);
        background-color: var(--sliderColor);
        cursor: pointer;
        border: var(--sliderCircleBorder);
        border-radius: 100%;
      } 
        
      .min-max-slider > .range-input::-webkit-slider-runnable-track,  
      .min-max-slider > .range-input::-moz-range-track {
        cursor: pointer;
      }
        
      .min-max-slider > .range-input:focus::-webkit-slider-thumb {
        /* Accessible border on focus */
        border: var(--sliderCircleFocusBorder);
      }

      .min-max-slider > .range-input:focus::-moz-range-thumb {
          /* Accessible border on focus */
          border: var(--sliderCircleFocusBorder);
      }
        
      span.value {
        --labelBeforeContent: '';
        --labelAfterContent: '';
        --labelFontSize: 16px;
        --labelFontWeight: bold;
        font-size: var(--labelFontSize);
        font-weight: var(--labelFontWeight);
        height: auto;
        display: inline-block;
      }

      span.value::before {
        content: var(--labelBeforeContent);
      }

      span.value::after {
        content: var(--labelAfterContent);
      }

      .range-input-dash-icon {
        padding: 0 `).concat(l.sliderCommonSize,`;
      }

      .range-input-label {
          border: 1px solid `).concat(l.sliderBorderColor,`;
          border-radius: `).concat(l.sliderBorderRadius,`;
          padding: `).concat(l.sliderCommonSize,`;
      }
    </style>
    
    <div id="`).concat(i.SLIDER_ID,`" class="min-max-slider"></div>
`);var I=function(){_(o,f(HTMLElement));var s=R(o);function o(){var e;return A(this,o),(e=s.call(this)).emitRangeSelection=function(){return e.emitRange()},e.onRangeInput=function(t){return e.update(t.target)},e.attachShadow({mode:"open"}),e.shadowRoot.appendChild(y.content.cloneNode(!0)),e}return E(o,[{key:"sliderId",get:function(){return this.getAttribute("id")}},{key:"minRange",get:function(){return parseInt(this.getAttribute("min-range"))||parseInt(this.getAttribute("min"))||1},set:function(e){this.setAttribute("min-range",e),this.setAttribute("min",e)}},{key:"maxRange",get:function(){return parseInt(this.getAttribute("max-range"))||parseInt(this.getAttribute("max"))||0},set:function(e){this.setAttribute("max-range",e),this.setAttribute("max",e)}},{key:"presetMin",get:function(){return parseInt(this.getAttribute("preset-min"))},set:function(e){this.setAttribute("preset-min",e)}},{key:"presetMax",get:function(){return parseInt(this.getAttribute("preset-max"))},set:function(e){this.setAttribute("preset-max",e)}},{key:"numberOfLegendItemsToShow",get:function(){var e=parseInt(this.getAttribute("number-of-legend-items-to-show"));return e&&1<e?e:2}},{key:"hideLegend",get:function(){return this.hasAttribute("hide-legend")}},{key:"hideLabel",get:function(){return this.hasAttribute("hide-label")}},{key:"inputsForLabels",get:function(){return this.hasAttribute("inputs-for-labels")}},{key:"sliderColor",get:function(){return this.getAttribute("slider-color")}},{key:"circleColor",get:function(){return this.getAttribute("circle-color")}},{key:"circleBorderColor",get:function(){return this.getAttribute("circle-border-color")}},{key:"circleFocusBorderColor",get:function(){return this.getAttribute("circle-focus-border-color")}},{key:"circleBorder",get:function(){return this.getAttribute("circle-border")}},{key:"circleFocusBorder",get:function(){return this.getAttribute("circle-focus-border")}},{key:"circleSize",get:function(){return this.getAttribute("circle-size")}},{key:"labelAfterContent",get:function(){return this.getAttribute("label-after")}},{key:"labelBeforeContent",get:function(){return this.getAttribute("label-before")}},{key:"labelFontWeight",get:function(){return this.getAttribute("label-font-weight")}},{key:"labelFontSize",get:function(){return this.getAttribute("label-font-size")}},{key:"eventNameToEmitOnChange",get:function(){return this.getAttribute("event-name-to-emit-on-change")||i.CUSTOM_EVENT_TO_EMIT_NAME}},{key:"attributeChangedCallback",value:function(e,t,n){switch(e){case"min-label":if(!n)return;var r=this.getEl(i.MIN_LABEL_ID);if(!r)return;r.innerText=n;break;case"max-label":if(!n||(r=this.getEl(i.MAX_LABEL_ID),!r))return;r.innerText=n;break;case"min-range":case"min":if(isNaN(n)||t===n)return;this.minRange=n;break;case"max-range":case"max":if(isNaN(n)||t===n)return;this.maxRange=n;break;case"preset-min":if(isNaN(n)||t===n)return;this.presetMin=n;break;case"preset-max":if(isNaN(n)||t===n)return;this.presetMax=n}this.init()}},{key:"connectedCallback",value:function(){this.init(),new ResizeObserver(this.onResize(this)).observe(this.shadowRoot.querySelector(".min-max-slider"))}},{key:"disconnectedCallback",value:function(){var e=this.getEl(i.SLIDER_ID),t=e.querySelector("#".concat(i.MIN));this.removeEventListeners(t,i.RANGE_STOPPED_EVENTS,this.emitRangeSelection,!1),this.removeEventListeners(t,["input"],this.onRangeInput,!1);var t=e.querySelector("#".concat(i.MAX));this.removeEventListeners(t,i.RANGE_STOPPED_EVENTS,this.emitRangeSelection,!1),this.removeEventListeners(t,["input"],this.onRangeInput,!1),this.inputsForLabels&&(t=e.querySelector("[".concat(i.RANGE_INPUT_DATA_LABEL_MIN,"]")),e=e.querySelector("[".concat(i.RANGE_INPUT_DATA_LABEL_MAX,"]")),this.removeEventListeners(t,["input"],this.onRangeInput,!1),this.removeEventListeners(e,["input"],this.onRangeInput,!1),this.removeEventListeners(t,["blur"],this.emitRangeSelection,!1),this.removeEventListeners(e,["blur"],this.emitRangeSelection,!1))}},{key:"dispatchCustomEvent",value:function(e,t){(e||window).dispatchEvent(t)}},{key:"addMultipleEventListeners",value:function(e,t,n){t.forEach(function(r){return e.addEventListener(r,n)})}},{key:"removeEventListeners",value:function(e,t,n,r){t.forEach(function(a){e.removeEventListener(a,n,r)})}},{key:"getAverage",value:function(e,t){return Math.floor((e+t)/2)}},{key:"getEl",value:function(e){return this.shadowRoot.getElementById(e)}},{key:"init",value:function(){var e=this.getEl(i.SLIDER_ID);this.setInitialSliderState(e),this.setupStyles();var t=e.querySelector("#".concat(i.MIN)),n=e.querySelector("#".concat(i.MAX));this.setupPresetValues(t,n),this.createLabels(e,t),this.createLegend(e),this.draw(e,this.getAverage(this.presetMin||this.minRange,this.presetMax||this.maxRange)),t.addEventListener("input",this.onRangeInput),n.addEventListener("input",this.onRangeInput),this.addMultipleEventListeners(t,i.RANGE_STOPPED_EVENTS,this.emitRangeSelection),this.addMultipleEventListeners(n,i.RANGE_STOPPED_EVENTS,this.emitRangeSelection),this.setupResetFunctionality()}},{key:"onResize",value:function(e){return function(t){t=t[0].target,e.update(t.querySelector(".range-input"))}}},{key:"setInitialSliderState",value:function(e){e.innerHTML=`
      <label id="`.concat(i.MIN_LABEL_ID,'" for="').concat(i.MIN,`">Minimum</label> 
      <input id="`).concat(i.MIN,'" class="range-input" name="').concat(i.MIN,`" type="range" step="1" />
      <label id="`).concat(i.MAX_LABEL_ID,'" for="').concat(i.MAX,`">Maximum</label>
      <input id="`).concat(i.MAX,'" class="range-input" name="').concat(i.MAX,`" type="range" step="1" />
    `)}},{key:"setupPresetValues",value:function(e,t){var n=this.presetMin&&this.presetMin<this.presetMax?this.presetMin:this.minRange,r=this.presetMax&&this.presetMax>this.presetMin?this.presetMax:this.maxRange;e.setAttribute("data-value",n),t.setAttribute("data-value",r),e.value=n,t.value=r}},{key:"setupResetFunctionality",value:function(){var e=this;window.addEventListener("range-reset",function(t){t.detail&&t.detail.sliderId&&t.detail.sliderId!==e.sliderId||e.init()})}},{key:"emitRange",value:function(){var e=this.getEl(i.SLIDER_ID),t=e.querySelector("#".concat(i.MIN)),n=e.querySelector("#".concat(i.MAX));this.dispatchCustomEvent(e,new CustomEvent(this.eventNameToEmitOnChange,{bubbles:!0,composed:!0,detail:{sliderId:this.sliderId,minRangeValue:Math.floor(t.getAttribute("data-value")),maxRangeValue:Math.floor(n.getAttribute("data-value"))}}))}},{key:"draw",value:function(e,u){var n=e.querySelector("#".concat(i.MIN));n.setAttribute(i.MIN,this.minRange),n.setAttribute(i.MAX,u);var r=e.querySelector("#".concat(i.MAX));r.setAttribute(i.MIN,u),r.setAttribute(i.MAX,this.maxRange);var d=e.offsetWidth,a=l.sliderCircleSize;n.style.width="".concat(parseInt(a+(u-this.minRange)/(this.maxRange-this.minRange)*(d-2*a)),"px"),r.style.width="".concat(parseInt(a+(this.maxRange-u)/(this.maxRange-this.minRange)*(d-2*a)),"px"),n.style.left="0px",r.style.left="".concat(parseInt(n.style.width),"px");var c,u=e.querySelector(".lower"),d=n.offsetHeight;this.hideLabel||(c=this.inputsForLabels?u.offsetHeight+5:u.offsetHeight,n.style.top="".concat(c,"px"),r.style.top="".concat(c,"px"),d+=c),this.hideLegend||(a=e.querySelector(".legend"),c=this.inputsForLabels?n.offsetHeight+5:n.offsetHeight,a.style.paddingTop="".concat(c,"px"),d+=+a.offsetHeight),e.style.height="".concat(d,"px"),r.value=r.getAttribute("data-value"),n.value=n.getAttribute("data-value"),this.hideLabel||(e=e.querySelector(".upper"),this.inputsForLabels?(u.value=n.getAttribute("data-value"),e.value=r.getAttribute("data-value")):(u.innerHTML=n.getAttribute("data-value"),e.innerHTML=r.getAttribute("data-value")))}},{key:"update",value:function(e){var t=e.parentElement,r=e.hasAttribute(i.RANGE_INPUT_DATA_LABEL_MIN)||e.hasAttribute(i.RANGE_INPUT_DATA_LABEL_MAX)?(n="[".concat(i.RANGE_INPUT_DATA_LABEL_MIN,"]"),"[".concat(i.RANGE_INPUT_DATA_LABEL_MAX,"]")):(n="#".concat(i.MIN),"#".concat(i.MAX)),n=t.querySelector(n),r=t.querySelector(r),n=Math.floor(parseInt(n.value)),r=Math.floor(parseInt(r.value));this.isValidRangeSelection(e,n,r)&&(t.querySelector("#".concat(i.MIN)).setAttribute("data-value",n),t.querySelector("#".concat(i.MAX)).setAttribute("data-value",r),this.draw(t,this.getAverage(n,r)))}},{key:"isValidRangeSelection",value:function(e,t,n){var r=this;return this.inputsForLabels?e.hasAttribute(i.RANGE_INPUT_DATA_LABEL_MIN)||e.getAttribute("id")===i.MIN?function(a){return r.minRange&&a<r.maxRange&&a<n}:n>this.minRange&&n<=this.maxRange&&t<n:t!==n}},{key:"setupStyles",value:function(){var e=this;this.shadowRoot.querySelectorAll(".min-max-slider > .range-input").forEach(function(t){e.sliderColor&&(t.style.backgroundImage="linear-gradient(to bottom, transparent 0%, transparent 30%, ".concat(e.sliderColor," 30%, ").concat(e.sliderColor," 60%, transparent 60%, transparent 100%)")),e.circleColor&&t.style.setProperty("--sliderColor",e.circleColor),e.circleBorderColor&&t.style.setProperty("--sliderBorderColor",e.circleBorderColor),e.circleFocusBorderColor&&t.style.setProperty("--sliderFocusBorderColor",e.circleFocusBorderColor),e.circleBorder&&t.style.setProperty("--sliderCircleBorder",e.circleBorder),e.circleFocusBorder&&t.style.setProperty("--sliderCircleFocusBorder",e.circleFocusBorder),e.circleSize&&t.style.setProperty("--sliderCircleSize",e.circleSize)})}},{key:"createLegend",value:function(e){if(!this.hideLegend){var t=document.createElement("div");t.classList.add("legend");for(var n=[],r=0;r<this.numberOfLegendItemsToShow;r++){n[r]=document.createElement("div");var a=Math.round(this.minRange+r/(this.numberOfLegendItemsToShow-1)*(this.maxRange-this.minRange));n[r].appendChild(document.createTextNode(a)),t.appendChild(n[r])}e.appendChild(t)}}},{key:"setupLabelStyles",value:function(e,t){function n(r,a){e.style.setProperty(r,a),t.style.setProperty(r,a)}this.labelAfterContent&&n("--labelAfterContent","'".concat(this.labelAfterContent,"'")),this.labelBeforeContent&&n("--labelBeforeContent","'".concat(this.labelBeforeContent,"'")),this.labelFontWeight&&n("--labelFontWeight",this.labelFontWeight),this.labelFontSize&&n("--labelFontSize",this.labelFontSize)}},{key:"createLabels",value:function(e,t){var n,r,a;this.hideLabel||(n=this.inputsForLabels?"input":"span",r=document.createElement(n),a=document.createElement(n),r.classList.add("range-".concat(n,"-label"),"lower","value"),a.classList.add("range-".concat(n,"-label"),"upper","value"),this.setupLabelStyles(r,a),this.inputsForLabels?(r.value=this.minRange,a.value=this.maxRange,r.setAttribute("type","number"),r.setAttribute(i.MAX,this.minRange),r.setAttribute(i.MAX,this.maxRange),r.setAttribute(i.RANGE_INPUT_DATA_LABEL_MIN,""),a.setAttribute("type","number"),a.setAttribute(i.MIN,this.minRange),a.setAttribute(i.MAX,this.maxRange),a.setAttribute(i.RANGE_INPUT_DATA_LABEL_MAX,""),r.addEventListener("input",this.onRangeInput),a.addEventListener("input",this.onRangeInput),r.addEventListener("blur",this.emitRangeSelection),a.addEventListener("blur",this.emitRangeSelection)):(r.appendChild(document.createTextNode(this.minRange)),a.appendChild(document.createTextNode(this.maxRange))),e.insertBefore(r,t.previousElementSibling),e.insertBefore(a,t.previousElementSibling),(a=document.createElement("i")).classList.add("range-input-dash-icon"),a.setAttribute("aria-hidden",!0),a.innerHTML="&#65123",e.insertBefore(a,t.previousElementSibling.previousElementSibling))}}],[{key:"observedAttributes",get:function(){return["min-label","max-label","min-range","max-range","min","max","preset-min","preset-max"]}}]),o}();window.customElements.define("range-selector",I);
