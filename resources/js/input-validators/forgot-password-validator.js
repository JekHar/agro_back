class pageAuthReminder {
    static initValidation() {
        One.helpers('jq-validation');

        jQuery('.js-validation-reminder').validate({
            rules: {
                'email': {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                'email': {
                    required: __('auth.reminder.required', 'Please enter a valid credential'),
                    minlength: __('auth.reminder.minlength', 'Your credential must be at least 3 characters')
                }
            }
        });
    }

    static init() {
        window.addEventListener('lang-changed', () => {
            this.initValidation();
        });

        this.initValidation();
    }
}

One.onLoad(() => pageAuthReminder.init());
