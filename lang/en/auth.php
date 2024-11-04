<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'password_validator' => [
        'required' => 'Please provide a password',
        'minlength' => 'Your password must be at least 6 characters long',
        'confirm' => [
            'required' => 'Please provide a password',
            'minlength' => 'Your password must be at least 6 characters long',
            'equal' => 'Please enter the same password as above',
        ],
    ],
    'email_validator' => [
        'required' => 'Please enter a valid email address',
        'minlength' => 'Your email must consist of at least 3 characters',
    ],
    'signup' => [
        'username' => [
            'required' => 'Please enter a username',
            'minlength' => 'Your username must consist of at least 3 characters',
        ],
        'terms' => [
            'required' => 'You must agree to the service terms!',
        ],
    ],
    'reminder' => [
        'required' => 'Please enter a valid credential',
        'minlength' => 'Your credential must be at least 3 characters',
    ],
];
