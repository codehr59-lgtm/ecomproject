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

    'nagad' => [
        'merchant_id'  => env('NAGAD_MERCHANT_ID'),
        'merchant_key' => env('NAGAD_MERCHANT_KEY'),
        'sandbox'      => env('NAGAD_SANDBOX', true),
    ],

    'rocket' => [
        'merchant_id'       => env('ROCKET_MERCHANT_ID'),
        'merchant_password' => env('ROCKET_MERCHANT_PASSWORD'),
        'sandbox'           => env('ROCKET_SANDBOX', true),
    ],

];
