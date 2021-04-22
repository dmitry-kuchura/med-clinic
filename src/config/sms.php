<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Secret key for send SMS via Turbo SMS
    |--------------------------------------------------------------------------
    |
    | This token need to send in Authorization Bearer <secret>
    |
    */

    'secret' => env('TURBO_SMS_SECRET', null),

    /*
    |--------------------------------------------------------------------------
    | Name for messages SMS via Turbo SMS
    |--------------------------------------------------------------------------
    |
    | This showed in Viber / SMS
    |
    */

    'sender' => env('TURBO_SMS_SENDER', 'IT Alarm'),

    /*
    |--------------------------------------------------------------------------
    | Name for messages SMS via Turbo SMS
    |--------------------------------------------------------------------------
    |
    | This settings turn on or off send SMS
    |
    */

    'allowed' => (bool) env('TURBO_SMS_ALLOWED', 0),
];
