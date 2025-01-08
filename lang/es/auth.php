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
    'login' => 'Iniciar sesión',
    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña ingresada es incorrecta.',
    'throttle' => 'Demasiado intentos. Por favor, intenta de nuevo en :seconds seconds.',
    'password_validator' => [
        'required' => 'Por favor ingrese una contraseña',
        'minlength' => 'La contraseña debe tener al menos 6 caracteres',
        'confirm' => [
            'required' => 'Por favor ingrese una contraseña',
            'minlength' => 'La contraseña debe tener al menos 6 caracteres',
            'equal' => 'Por favor ingrese la misma contraseña',
        ],
    ],
    'email_validator' => [
        'required' => 'Por favor ingrese una dirección de email válida',
        'minlength' => 'La dirección de email debe tener al menos 3 caracteres',
    ],
    'signup' => [
        'username' => [
            'required' => 'Por favor ingrese un nombre de usuario',
            'minlength' => 'Tu nombre de usuario debe tener al menos 3 caracteres',
        ],
        'terms' => [
            'required' => 'Debe aceptar los términos y condiciones',
        ],
    ],
    'reminder' => [
        'required' => 'Por favor ingrese una contraseña',
        'minlength' => 'Tus credenciales deben tener al menos 3 caracteres',
    ],
];
