<?php

return [
    'uploads_path' => public_path('uploads'),
    'profile_image_path' => public_path('profile.png'),
    'whatsapp_url' => env('PORTFOLIO_WHATSAPP_URL', 'https://wa-server.shahabtech.com/api/v1/send-message'),
    'admin' => [
        'name' => env('PORTFOLIO_ADMIN_NAME', 'Portfolio Admin'),
        'email' => env('PORTFOLIO_ADMIN_EMAIL', 'admin@foliofy.local'),
        'password' => env('PORTFOLIO_ADMIN_PASSWORD', 'admin@2024'),
    ],
];
