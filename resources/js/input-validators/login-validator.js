class LoginValidator {
    static initValidation() {
        One.helpers('jq-validation');
        jQuery('.js-validation-signin').validate({
            rules: {
                'email': {
                    required: true,
                    minlength: 3
                },
                'password': {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                'email': {
                    required: __('auth.email_validator.required', 'Please enter an email'),
                    minlength: __('auth.email_validator.minlength', 'Email must be at least 3 characters')
                },
                'password': {
                    required: __('auth.password_validator.required', 'Please enter a password'),
                    minlength: __('auth.password_validator.minlength', 'Password must be at least 6 characters')
                }
            }
        });
    }

    static init() {
        this.initValidation();
    }
}

One.onLoad(() => LoginValidator.init());
