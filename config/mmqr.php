<?php

return [
    'environment' => env('MMQR_ENVIRONMENT', 'uat'),

    'uat' => [
        'base_url'  => env('MMQR_UAT_BASE_URL', 'https://uat-app.cbbank.com.mm:6007/dynamicqrapi/v1'),
        'user_id'   => env('MMQR_UAT_USER_ID', 'swezon'),
        'password'  => env('MMQR_UAT_PASSWORD', 'Fas0h^oUc-}zK61m6Eo3W£%JD'),
        'source_id' => env('MMQR_UAT_SOURCE_ID', 'swezon'),
        'mid'       => env('MMQR_UAT_MID', '223780111437170'),
        'tid'       => env('MMQR_UAT_TID', '52912778'),
    ],

    'production' => [
    'base_url'  => env('CB_BANK_MMQ_BASE_URL', 'https://myanmarpay.cbbank.com.mm:7443/dynamicqrapi/v1'),
    'user_id'   => env('CB_BANK_MMQ_USER_ID', 'EVENTPLUS'),
    'password'  => env('CB_BANK_MMQ_PASSWORD', 'c.nN0DfM6LQXP0i6+d!a-)tp:'),
    'source_id' => env('CB_BANK_MMQ_SOURCE_ID', 'EVENTPLUS'),
    'mid'       => env('CB_BANK_MMQ_MERCHANT_ID', '223780111843857'),
    'tid'       => env('CB_BANK_MMQ_TERMINAL_ID', '52000097'),
    'merchant_name' => env('CB_BANK_MMQ_MERCHANT_NAME', 'EVENTPLUS'),
    'public_ip' => env('CB_BANK_MMQ_PUBLIC_IP', '165.22.48.231'),
    'callback_url' => env('CB_BANK_MMQ_CALLBACK_URL', 'https://eventplus.com.mm/api/payment/mmqr/callback'),
],
];