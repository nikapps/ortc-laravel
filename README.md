# ORTC-Laravel (ORTC client for Laravel)

![Real-time Framework - ORTC](https://www.dropbox.com/s/z6by8jind9s3m5v/realtime.png?raw=1)

An Easy-To-Use [ORTC](http://framework.realtime.co/messaging) API Client for Laravel Framework (Laravel 5.1 and 5.2)

**If you're using Laravel 4.2.x, please check [branch l4](https://github.com/nikapps/ortc-laravel/tree/l4)**

*This package is based on [nikapps/ortc-php](https://github.com/nikapps/ortc-php).*

## Installation

You can install this [package](https://packagist.org/packages/nikapps/ortc-laravel) by simply run this composer command:

```
composer require nikapps/ortc-laravel
```

Then, add this service provider in your providers array `[app/config/app.php]`:

~~~php
Nikapps\OrtcLaravel\OrtcLaravelServiceProvider::class,
~~~

Then, add this Facade to your aliases array `[app/config/app.php]`:

~~~php
'Ortc' => Nikapps\OrtcLaravel\Facades\Ortc::class
~~~

Next you have to copy the configuration to your connections array `[app/config/broadcasting.php]`:

~~~php
'realtime' => [
    'driver' => 'realtime',
    'credentials' => [

        /*
         * your application key
         */
        'application_key' => 'YOUR_APPLICATION_KEY',
        /*
         * your private key
         */
        'private_key'     => 'YOUR_PRIVATE_KEY',

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
]
~~~


## Configuration

#### Get Application Key & Private Key
First of all, you should register on realtime.co and get your api keys.

* Login/Register at https://accounts.realtime.co

* Create new Subscription 

* You can see your `Application Key` and `Private Key`

* If you want to use authentication, you should enable it in your panel.

#### Update config file

Edit `app/config/broadcasting.php`:

~~~php
/*
* set default broadcasting driver to realtime
*/
'default' => env('BROADCAST_DRIVER', 'realtime'),

'credentials' => [

    /*
     * your application key
     */
    'application_key' => 'YOUR_APPLICATION_KEY',
    /*
     * your private key
     */
    'private_key'     => 'YOUR_PRIVATE_KEY',

],
~~~

#### Done!

## Usage

#### Get Balancer URL (Manually)

This package automatically get balancer url (best available server), but if you want fetch a new balancer url manually:

~~~php
$balancerUrl = Ortc::getBalancerUrl();

echo 'Balancer Url: ' . $balancerUrl->getUrl();
~~~

#### Authentication
In order to authenticate a user:

~~~php
$channels = [
	'channel_one' => 'w',
	'channel_two' => 'r'
];

Ortc::authenticate(
	$authToken, //authentication token
	$channels,
	$ttl, //(optional) default: 3600
	$isPrivate //(optional) default: false
);
~~~

#### Send Message via broadcasting service (recommend) (Push)
See [laravel/Events - Marking Events For Broadcast](http://laravel.com/docs/5.1/events#broadcasting-events)

Below a short example:

Create a new Event
```
php artisan make:event TestEvent
```
Open up app/Events/TestEvent.php and edit
~~~php
<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Add implements ShouldBroadcast to EventClass
 */
class TestEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * All public variables will be automatically added to the broadcast payload.
     */
    public $value;
    
    private $userId;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param $value
     * @return void
     */
    public function __construct(User $user, $value)
    {
        $this->userId = $user->id;
        $this->value = $value;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['userId_' . $userId];
    }
    
    /**
     * Get the broadcast event name.
     *
     * @return mixed also could be an array
     */
    public function broadcastAs()
    {
        return 'testEvent';
    }
}
~~~

To fire the event:

~~~php
$value = 'test 123';
event(new TestEvent($user, $value));
~~~

The result will be:

~~~json
{
    "event": "testEvent",
    "payload": {
    	"value": "test 123"
    }
}
~~~

#### Send Message manually (Push)
In order to push a message to a channel:

~~~php
Ortc::send(
	$channel, //channel name
	$authToken, //authentication token
	$message //message (string)
);
~~~

*If you are using UTF-8 messages, It's better to use `base64_encode()`.*

## Exceptions
See [nikapps/ortc-php - Exceptions](https://github.com/nikapps/ortc-php#exceptions)


## Dependencies

* [nikapps/ortc-php (1.x)](https://packagist.org/packages/nikapps/ortc-php)


## Ortc Documentation
This package is based on ORTC REST API. You can download REST service documentation from this url:

```
http://messaging-public.realtime.co/documentation/rest/2.1.0/RestServices.pdf
```

## TODO

* add UnitTest (codeception or phpunit)
* subscribe channel(s) by Ratchet/Nodejs
* support mobile push notification (ios & android)
* support presence channels
* Anything else?!

## Credits 

* Thanks to realtime.co teams for their amazing platform
* Thanks to [João Parreira](https://github.com/jparreira) for his php library
* Thanks to [rdarda](https://github.com/rdarda) for sending the pull request to support Laravel 5.1

## Contribute

Wanna contribute? simply fork this project and make a pull request!


## License
This project released under the [MIT License](http://opensource.org/licenses/mit-license.php).

```
/*
 * Copyright (C) 2015 NikApps Team.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * 1- The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * 2- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
```

## Donation

[![Donate via Paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G3WRCRDXJD6A8)
