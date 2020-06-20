<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "redis", "log", "null"
    |
    */

    'default' => env('PUBLISHER', 'crosref'),

    'services' => [
        'crosref' => [
            'url' => env('CROSREF_URL', ''),
            'document_endpoint' => env('CROSREF_ENDPOINT', '/works'),
            'headers' => [
                'user_agent' => env('CROSREF_USER_AGENT', ''),
            ],
        ],
    ],


];
