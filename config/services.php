<?php

return [

    'mail' => [
        'domain' => env('MAIL_DOMAIN'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
        'from' => env('MAIL_FROM_ADDRESS'),
        'name' => env('MAIL_FROM_NAME', 'Slip Gaji Karyawan'),
    ],

];
