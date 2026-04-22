<?php

return [
    'uploads_path' => public_path('uploads'),
    'profile_image_path' => public_path('profile.png'),
    'og_image_path' => storage_path('app/public/portfolio/og-image.jpg'),
    'og_image_width' => 1200,
    'og_image_height' => 630,
    'whatsapp_url' => env('PORTFOLIO_WHATSAPP_URL', 'https://wa-server.shahabtech.com/api/v1/send-message'),
    'admin' => [
        'name' => env('PORTFOLIO_ADMIN_NAME', 'Portfolio Admin'),
        'email' => env('PORTFOLIO_ADMIN_EMAIL', 'admin@foliofy.local'),
        'password' => env('PORTFOLIO_ADMIN_PASSWORD', 'admin@2024'),
    ],
];
