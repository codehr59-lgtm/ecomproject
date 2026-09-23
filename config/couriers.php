<?php

return [

    'pathao' => [
        'client_id'     => env('PATHAO_CLIENT_ID'),
        'client_secret' => env('PATHAO_CLIENT_SECRET'),
        'username'      => env('PATHAO_USERNAME'),
        'password'      => env('PATHAO_PASSWORD'),
        'sandbox'       => env('PATHAO_SANDBOX', true),
    ],

    'steadfast' => [
        'api_key'    => env('STEADFAST_API_KEY'),
        'api_secret' => env('STEADFAST_API_SECRET'),
    ],

];
