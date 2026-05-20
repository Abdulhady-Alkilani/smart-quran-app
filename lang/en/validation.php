<?php

return [
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a text.',
    'email' => 'Please enter a valid email address.',
    'max' => [
        'string' => 'The :attribute must not exceed :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'unique' => 'This :attribute is already registered. Please use a different :attribute or log in.',
    'confirmed' => 'The password confirmation does not match the password.',
    'current_password' => 'The current password is incorrect.',
    'lowercase' => 'The :attribute must be lowercase.',
    'password' => [
        'mixed' => 'The password must contain at least one uppercase and one lowercase letter.',
        'letters' => 'The password must contain at least one letter.',
        'symbols' => 'The password must contain at least one symbol.',
        'numbers' => 'The password must contain at least one number.',
        'uncompromised' => 'The given password has appeared in a data leak. Please choose a different password.',
    ],

    'attributes' => [
        'name' => 'Full Name',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
    ],
];
