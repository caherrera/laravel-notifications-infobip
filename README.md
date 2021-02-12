# Infobip Notifications Channel for Laravel 5.5+

[![Latest Stable Version](https://poser.pugx.org/caherrera/laravel-notifications-infobip/v/stable)](https://packagist.org/packages/caherrera/laravel-notifications-infobip)
[![License](https://poser.pugx.org/caherrera/laravel-notifications-infobip/license)](https://packagist.org/packages/caherrera/laravel-notifications-infobip)
[![Total Downloads](https://poser.pugx.org/caherrera/laravel-notifications-infobip/downloads)](https://packagist.org/packages/caherrera/laravel-notifications-infobip)

This package makes it easy to send Sms notifications using [Infobip service](https://dev.infobip.com/) with Laravel 5.5 and above.

## Contents

- [Installation](#installation)
	- [Setting up your Infobip account](#setting-up-your-infobip-account)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Examples](#examples)
	- [Dispatching the notification](#dispatching-the-notification)
	- [Example Notification class](#example-notification-class)
	- [Example Notifiable class](#example-notifiable-class)
- [Testing](#testing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation
You can install the package via composer:

``` bash
composer require caherrera/laravel-notifications-infobip
```

### Setting up your Infobip account
Add this settings to `config/services.php`:

```php
// config/services.php
...
    'infobip' => [
        'auth'        => env('INFOBIP_AUTH','basic'),
        'username'    => env('INFOBIP_USERNAME'),
        'password'    => env('INFOBIP_PASSWORD'),
        'baseUrl'     => env('INFOBIP_BASE_URL'),
        'apikey'      => env('INFOBIP_PUBLIC_KEY'),
        'scenarioKey' => env('INFOBIP_SCENARIO_KEY'),
    ],
...
```
To change `Base URL` to personal use this ([See more](https://dev.infobip.com/getting-started/base-url))

## Usage
Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Infobip\InfobipChannel;
use NotificationChannels\Infobip\InfobipMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [InfobipChannel::class];
    }

    public function toInfobip($notifiable)
    {
    	$message = new InfobipMessage();
		$message->setTemplateName("infobip_test_hsm");
		$message->setTemplateNamespace("whatsapp:hsm:it:infobip");
		$message->setTemplateData(["Jhon","Snow"]);
		$message->setLanguage("es");
        return $message;
    }
}
```

In order to let your Notification know which phone are you sending to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForInfobip` method to your Notifiable model.

```php
public function routeNotificationForInfobip()
{
    return '+1234567890';
}
```

### Available Message methods

#### InfobipMessage

- `setTemplateName('')`: Accepts a string value.
- `setTemplateNamespace('')`: Accepts a string value.
- `setTemplateData(['','',...])`: Accepts an array of string.
- `setLanguage('')`: Accepts a string value

## Examples

### Dispatching the notification

#### A. Using Laravel's notification facade

```php
use App\Notifications\ExampleInfobipNotification;
use Illuminate\Support\Facades\Notification;

Notification::send($user, new ExampleInfobipNotification());

// where $user implements `Illuminate\Notifications\Notifiable` trait
```

#### B. Using the `notify()` method from `Notifiable` trait

```php
use App\Notifications\ExampleInfobipNotification;

$user->notify(new ExampleInfobipNotification($invoice));
```

### Example Notification class

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Caherrera\Laravel\Notifications\Channels\Infobip\Omni\InfobipChannel;
use Caherrera\Laravel\Notifications\Channels\Infobip\Omni\InfobipMessage;

class ExampleInfobipNotification extends Notification
{
    public function via($notifiable)
    {
        return [InfobipChannel::class];
    }

    public function toInfobip($notifiable)
    {
        $message = new InfobipMessage();
		$message->setTemplateName("infobip_test_hsm");
		$message->setTemplateNamespace("whatsapp:hsm:it:infobip");
		$message->setTemplateData(["Jhon","Snow"]);
		$message->setLanguage("es");
        return $message;
        
    }
}
```

### Example Notifiable class

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
}
```


For more details you can check out [this link](https://laravel.com/docs/5.7/notifications#sending-notifications) on Laravel documentation


## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email princeton.mosha@gmail.com instead of using the issue tracker.

## Credits
- Based on [Twilio SMS Notification channel](https://github.com/laravel-notification-channels/twilio) for Laravel
- This project uses the [Infobip Client library](https://github.com/infobip/infobip-api-php-client), and wraps it for smooth use in Laravel

## License
The MIT License (MIT).