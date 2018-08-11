# SMS Broadcast notifications channel for Laravel 5.6

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hachetaustralia/smsbroadcast.svg?style=flat-square)](https://packagist.org/packages/hachetaustralia/smsbroadcast)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/hachetaustralia/smsbroadcast.svg?style=flat-square)](https://packagist.org/packages/hachetaustralia/smsbroadcast)

This package makes it easy to send SMS Broadcast SMS notifications with Laravel 5.6.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Setting up your SMSBroadcast account](#setting-up-your-smsbroadcast-account)
- [Usage](#usage)
    - [Available methods](#available-methods)
    - [Available events](#available-events)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements

- [Sign up](https://www.smsbroadcast.com.au/join) for a free SMS Broadcast account

## Installation

You can install the package via composer:

``` bash
composer require hachetaustralia/smsbroadcast
```

for Laravel 5.4 or lower, you must add the service provider to your config:

```php
// config/app.php
...
'providers' => [
    ...
    NotificationChannels\SMSBroadcast\SMSBroadcastServiceProvider::class,
],
```

## Setting up your SMSBroadcast account

Add the environment variables to your `config/services.php`:

```php
// config/services.php
...
'smsbroadcast' => [
    'username' => env('SMSBROADCAST_USERNAME'),
    'password' => env('SMSBROADCAST_PASSWORD'),
    'from' => env('SMSBROADCAST_FROM'),
    'sandbox' => env('SMSBROADCAST_SANDBOX'),
],
...
```

Add your SMS Broadcast username and password as well as the default from number/alphanumeric code to your `.env`:

```php
// .env
...
SMSBROADCAST_USERNAME=
SMSBROADCAST_PASSWORD=
SMSBROADCAST_FROM=
SMSBROADCAST_SANDBOX=false
],
...
```

Notice: The from can contain a maximum of 11 alphanumeric characters. You can also specify sandbox to true for testing (no post requests are made).

Setup your route on your `notifiable` model such as your User with the default destination for that model (single number or array of numbers).

``` php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForSmsBroadcast()
    {
        return $this->mobile;
    }
}
```

``` php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForSmsBroadcast()
    {
        return [ $this->mobile_primary, $this->mobile_secondary ];
    }
}
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\SMSBroadcast\SMSBroadcastChannel;
use NotificationChannels\SMSBroadcast\SMSBroadcastMessage;
use Illuminate\Notifications\Notification;

class VpsServerOrdered extends Notification
{
    public function via($notifiable)
    {
        return [SMSBroadcastChannel::class];
    }

    public function toSMSBroadcast($notifiable)
    {
        return (new SMSBroadcastMessage("Your {$notifiable->service} was ordered!"));
    }
}
```

### Available methods

Additionally you can add or change recipients (single value or array)

``` php
return (new SMSBroadcastMessage("Your {$notifiable->service} was ordered!"))->setRecipients($recipients);
```

In order to handle a status report you can also set a reference

``` php
return (new SMSBroadcastMessage("Your {$notifiable->service} was ordered!"))->setReference($id);
```

Maximum message splits are supported as well to determine the maximum number of SMS message credits to use per recipient. This defaults to 1.

``` php
return (new SMSBroadcastMessage("Your {$notifiable->service} was ordered!"))->setMaxSplit(2);
```

You can also delay message sending by a specified number of minutes

``` php
return (new SMSBroadcastMessage("Your {$notifiable->service} is ready to go!"))->setDelay(10);
```

Setting a private reference will not transmit to SMS Broadcast and be available should you need it on the `MessageWasSent` event as a property of the `SMSBroadcastMessage`. This is useful if you want to set something like a foreign key that you can utilise on a listener listening to the `MessageWasSent` event.

``` php
return (new SMSBroadcastMessage("Your {$notifiable->service} is ready to go!"))->setPrivateReference(12345);
```

If you wish to use SMS Broadcast's default two-way SMS number as the from number, simply `setNoFrom()` on the message instance

``` php
return (new SMSBroadcastMessage("Your {$notifiable->service} is ready to go!"))->setNoFrom();
```

### Available events

SMS Broadcast Notification channel comes with handy events which provides the required information about the SMS messages.

1. **Message Was Sent** (`NotificationChannels\SMSBroadcast\Events\MessageWasSent`)

Example:

```php
namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\SMSBroadcast\Events\MessageWasSent;

class SentMessageHandler
{
    /**
     * Handle the event.
     *
     * @param  MessageWasSent  $event
     * @return void
     */
    public function handle(MessageWasSent $event)
    {
        $response = $event->response;
        $message = $event->message;
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email support@hachet.com.au instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Hatchet](https://hatchet.com.au)

## License

<<<<<<< HEAD
The `NoHarm` Licence. Please see [License File](LICENSE.md) for more information.
=======
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
>>>>>>> 3e4067912060b47a570815438939a75be467b44b
