// app.js
import Template from "./modules/template.js";
import LangHelper from "./modules/lang-helper.js";

export default class App extends Template {
    constructor(options) {
        super(options);

        this.lang = new LangHelper(LARAVEL_TRANSLATIONS);
        window.__ = (key, defaultValue) => this.lang.__(key, defaultValue);
        window.Lang = this.lang;
        console.debug('Current locale:', this.lang.getLocale());
    }
}

window.One = new App({ darkMode: "system" });
