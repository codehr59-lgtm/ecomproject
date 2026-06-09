<?php

return [

    'sslcommerz' => [
        'store_id'       => env('SSLCOMMERZ_STORE_ID'),
        'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),
        'sandbox'        => env('SSLCOMMERZ_SANDBOX', true),
    ],

    'bkash' => [
        'app_key'    => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username'   => env('BKASH_USERNAME'),
        'password'   => env('BKASH_PASSWORD'),
        'sandbox'    => env('BKASH_SANDBOX', true),
    ],

];
