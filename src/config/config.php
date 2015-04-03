<?php
return [
    /*
    |--------------------------------------------------------------------------
    | RealTime.co Credentials
    |--------------------------------------------------------------------------
    | you should set your realtime credentials.
    | @see https://accounts.realtime.co
    |
    */
    'credentials' => [

        /*
         * your application key
         */
        'application_key' => 'your-applicatoin-key',
        /*
         * your private key
         */
        'private_key'     => 'your-client-secret',

    ],
    /*
    |--------------------------------------------------------------------------
    | Real-time REST API Options
    |--------------------------------------------------------------------------
    | you can change default options of api.
    |
    */
    'api'         => [
        /*
         * send message
         */
        'send_message'   => [
            'path'               => '/send', //api path
            'max_chunk_size'     => 700, //maximum size of each message in bytes
            'batch_pool_size'    => 5, //pool size for concurrent requests
            'pre_message_string' => '{RANDOM}_{PART}-{TOTAL_PARTS}_' //pre message string format
        ],
        /*
         * authentication
         */
        'authentication' => [
            'path' => '/authenticate' //api path
        ],
        /*
         * url to fetch balancer url
         */
        'balancer_url'   => 'https://ortc-developers.realtime.co/server/2.1?appkey={APP_KEY}',
        /*
         * verify ssl/tls certificate
         */
        'verify_ssl'     => true
    ]
];
