<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'twitter' => [
            'oauth_callback' => "http://localhost:8083/slim_skele/public/callback.php",
            //'oauth_access_token' => "aauzwwAAAAAA2NWvAAABXlhqWTI",
            //'oauth_access_token_secret' => "E8iJVio81jcJN6SMIKVvbk7Z6Ca2DOGv",
            'consumer_key' => "GvHS0rNxfTRRChti5MGsIWlOA",
            'consumer_secret' => "iHz6wDlwVMKKcgDOF9fGYseZS4qYXBU931B4UfMDN2cWh3nsQD"
        ]
    ],
];
