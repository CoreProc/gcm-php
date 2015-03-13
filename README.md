Google Cloud Messaging Library For PHP
========

A PHP library that allows you to send messages / push notifications to devices with your Android application installed.

[![Author](http://img.shields.io/badge/author-@chrisbjr-blue.svg?style=flat-square)](https://twitter.com/chrisbjr)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Quick start

### Required setup

The easiest way to install this library is via Composer.

Create a `composer.json` file and enter the following:

    {
        "require": {
            "coreproc/gcm": "0.1.*"
        }
    }

If you haven't yet downloaded your composer file, you can do so by executing the following in your command line:

    curl -sS https://getcomposer.org/installer | php

Once you've downloaded the composer.phar file, continue with your installation by running the following:

    php composer.phar install

## Usage

### Basic Usage

The example below gives you a bare minimum of what to do to send a message / push notification.

```php
<?php

require 'vendor/autoload.php';

use Coreproc\Gcm\GcmClient;
use Coreproc\Gcm\Classes\Message;

$gcmClient = new GcmClient('your-gcm-api-key-here');

$message = new Message($gcmClient);

$message->addRegistrationId('xxxxxxxxxx');
$message->setData([
    'title' => 'Sample Push Notification',
    'message' => 'This is a test push notification using Google Cloud Messaging'
]);

// More options are available in the Message class

try {
    
    $response = $message->send();
    
    // The send() method returns a Response object
    print_r($response);
    
} catch (Exception $exception) {
    
    echo 'uh-oh: ' . $exception->getMessage();
    
}
```

### How to obtain a GCM API key

1. Log in to [https://console.developers.google.com](https://console.developers.google.com)
2. Create a new project and select the project after the project is created.
3. Select the "APIs" option on the left menu sidebar.
4. Look for "Google Cloud Messaging for Android" and turn it on.
5. Next, go to "Credentials" option on the left menu sidebar.
6. Click on the "Create new Key" button and make a new "Server Key".
7. Enter the IP address of your server and hit "Create".
8. Your API key should now appear on the page.

### More Information

All options from the [https://developer.android.com/google/gcm/http.html](GCM HTTP Connection) are implemented in this library and they can be found in the `Message` class.

For more documentation on what options you can use, please refer to: [https://developer.android.com/google/gcm/http.html](https://developer.android.com/google/gcm/http.html).