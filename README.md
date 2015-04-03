# ORTC-Laravel (ORTC client for Laravel)

![Real-time Framework - ORTC](https://www.dropbox.com/s/z6by8jind9s3m5v/realtime.png?raw=1)

An Easy-To-Use ORTC API Client package for Laravel Framework (Laravel 4.2.x)

*This package is based on [nikapps/ortc-php](https://github.com/nikapps/ortc-php).*

## Installation

Simply run command:

```
composer require nikapps/ortc-laravel
```

Or you can add this [package](https://packagist.org/packages/nikapps/ortc-laravel) dependency to your Laravel's composer.json :

~~~json
{
    "require": {
        "nikapps/ortc-laravel": "1.*"
    }
    
}
~~~

Then update composer:

```
composer update
```

-

Add this package provider in your providers array `[app/config/app.php]`:

~~~php
'Nikapps\OrtcLaravel\OrtcLaravelServiceProvider',
~~~

Next you need to publish configuration file. Run this command:

```
php artisan config:publish nikapps/ortc-laravel
```


## Configuration

#### Get Application Key & Private Key
First of all, you should register on realtime.co and get your api keys.

* Login/Register at https://accounts.realtime.co

* Create new Subscription 

* You can see your `Application Key` and `Private Key`

* If you want to use authentication, you should enable it in your panel.

#### Update config file

Edit `app/config/packages/nikapps/ortc-laravel/config.php`:

~~~php
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

#### Send Message (Push)
In order to push a message to a channel:

~~~php
Ortc::send(
	$channel, //channel name
	$authToken, //authentication token
	$message //message (string)
);
~~~

*If you using UTF-8 messages, it's better to use `base64_encode()`.*

## Exceptions
See [nikapps/ortc-php - Exceptions](https://github.com/nikapps/ortc-php#exceptions)


## Dependencies

* [nikapps/ortc-php (1.x)](https://packagist.org/packages/nikapps/ortc-php)


## Ortc Documentation
This package is based on ORTC REST API. You can download REST service documentation from this url:

```
http://ortc.xrtml.org/documentation/rest/2.1.0/RestServices.pdf
```

## TODO

* add UnitTest (codeception or phpunit)
* subscribe channel(s) by Ratchet/Nodejs
* support mobile push notification (ios & android)
* support presence channels
* create package for Laravel 5
* Anything else?!

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
