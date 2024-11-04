
class RegisterValidator {
    static initValidation() {
        One.helpers('jq-validation');

        jQuery('.js-validation-signup').validate({
            rules: {
                'name': {
                    required: true,
                    minlength: 3
                },
                'email': {
                    required: true,
                    emailWithDot: true
                },
                'password': {
                    required: true,
                    minlength: 5
                },
                'password_confirmation': {
                    required: true,
                    equalTo: '#password'
                },
                'terms': {
                    required: true
                }
            },
            messages: {
                'name': {
                    required: __('auth.signup.username.required'),
                    minlength: __('auth.signup.username.minlength')
                },
                'email': __('auth.email_validator.required'),
                'password': {
                    required: __('auth.password_validator.required'),
                    minlength: __('auth.password_validator.minlength')
                },
                'password_confirmation': {
                    required: __('auth.password_validator.confirm.required'),
                    minlength: __('auth.password_validator.confirm.minlength'),
                    equalTo: __('auth.password_validator.confirm.equal')
                },
                'terms': __('auth.signup.terms.required')
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

One.onLoad(() => RegisterValidator.init());
