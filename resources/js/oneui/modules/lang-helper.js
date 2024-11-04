export default class LangHelper {
    constructor(translations) {
        this.translations = translations;
        this.currentLocale = this.getInitialLocale();
    }

    /**
     * Get initial locale with priority chain:
     * localStorage > cookie > html lang > browser locale > fallback
     * @returns {string}
     * @private
     */
    getInitialLocale() {
        const localStorageLocale = localStorage.getItem('locale');
        if (localStorageLocale && this.translations[this.normalizeLocale(localStorageLocale)]) {
            return this.normalizeLocale(localStorageLocale);
        }

        const cookieLocale = this.getCookie('locale');
        if (cookieLocale && this.translations[this.normalizeLocale(cookieLocale)]) {
            return this.normalizeLocale(cookieLocale);
        }

        const htmlLang = document.documentElement.lang;
        if (htmlLang && this.translations[this.normalizeLocale(htmlLang)]) {
            return this.normalizeLocale(htmlLang);
        }

        const browserLocale = navigator.language;
        if (this.translations[this.normalizeLocale(browserLocale)]) {
            return this.normalizeLocale(browserLocale);
        }

        return 'en';
    }

    /**
     * Normalize locale code (e.g., 'es-ES' or 'es_ES' to 'es')
     * @param {string} locale
     * @returns {string}
     * @private
     */
    normalizeLocale(locale) {
        return locale.split(/[-_]/)[0];
    }

    /**
     * Get cookie value by name
     * @param {string} name
     * @returns {string|null}
     * @private
     */
    getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    /**
     * Get translation for a key with optional default value
     * @param {string} key - Dot notation key (e.g., 'auth.email.required')
     * @param {string|null} defaultValue - Optional default value if translation not found
     * @returns {string}
     */
    __(key, defaultValue = null) {
        const result = key.split('.').reduce((obj, i) => obj?.[i], this.translations[this.currentLocale]);
        return result || defaultValue || key;
    }

    /**
     * Change current locale and persist it
     * @param {string} locale
     * @param {boolean} persist - Whether to save in localStorage and cookie
     * @returns {boolean} success
     */
    setLocale(locale, persist = true) {
        const normalizedLocale = this.normalizeLocale(locale);

        if (this.translations[normalizedLocale]) {
            this.currentLocale = normalizedLocale;

            if (persist) {
                localStorage.setItem('locale', normalizedLocale);
                document.cookie = `locale=${normalizedLocale};path=/;max-age=31536000`;
            }

            document.documentElement.lang = locale;
            window.dispatchEvent(new CustomEvent('lang-changed', { detail: { locale: normalizedLocale } }));
            return true;
        }
        console.warn(`Locale ${locale} not found in translations`);
        return false;
    }

    /**
     * Get current locale
     * @returns {string}
     */
    getLocale() {
        return this.currentLocale;
    }

    /**
     * Check if a translation exists
     * @param {string} key
     * @returns {boolean}
     */
    has(key) {
        const segments = key.split('.');
        let result = this.translations[this.currentLocale];

        for (const segment of segments) {
            if (result && typeof result === 'object') {
                result = result[segment];
            } else {
                return false;
            }
        }

        return result !== undefined;
    }
}
