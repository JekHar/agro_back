class ResetPasswordValidator {
    static initValidation() {
        One.helpers('jq-validation');

        jQuery('.js-validation-reminder').validate({
            rules: {
                'email': {
                    required: true,
                    minlength: 3
                },
                'password': {
                    required: true,
                    minlength: 5
                },
                'password_confirmation': {
                    required: true,
                    equalTo: '#password'
                },
            },

            messages: {
                'email': {
                    required: __('auth.email_validator.required'),
                    minlength: __('auth.email_validator.minlength')
                },
                'password': {
                    required: __('auth.password_validator.required'),
                    minlength: __('auth.password_validator.minlength')
                },
                'password_confirmation': {
                    required: __('auth.password_validator.confirm.required'),
                    minlength: __('auth.password_validator.confirm.minlength'),
                    equalTo: __('auth.password_validator.confirm.equal')
                }
            },
        });
    }

    static init() {
        window.addEventListener('lang-changed', () => {
            this.initValidation();
        });

        this.initValidation();
    }
}

One.onLoad(() => ResetPasswordValidator.init());
