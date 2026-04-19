<?php

return [
    'messages_path' => storage_path('app/messages.json'),
    'uploads_path' => public_path('uploads'),
    'profile_image_path' => public_path('profile.png'),
    'whatsapp_url' => env('PORTFOLIO_WHATSAPP_URL', 'https://wa-server.shahabtech.com/api/v1/send-message'),
];
