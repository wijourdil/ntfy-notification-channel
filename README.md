# ntfy.sh Notification Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wijourdil/ntfy-notification-channel.svg?style=flat-square)](https://packagist.org/packages/wijourdil/ntfy-notification-channel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/wijourdil/ntfy-notification-channel/run-tests?label=tests)](https://github.com/wijourdil/ntfy-notification-channel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/wijourdil/ntfy-notification-channel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/wijourdil/ntfy-notification-channel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wijourdil/ntfy-notification-channel.svg?style=flat-square)](https://packagist.org/packages/wijourdil/ntfy-notification-channel)

This package adds a Laravel Notification Channel to sent messages via [ntfy](https://ntfy.sh).
It's build on top of the [verifiedjoseph/ntfy-php-library](https://github.com/VerifiedJoseph/ntfy-php-library) package.

## Installation

Install the package via composer:

```bash
composer require wijourdil/ntfy-notification-channel
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="ntfy-notification-channel-config"
```

## Configuration

If you are using the online version https://ntfy.sh, you don't need to configure the server base url.
But if you are using a self-hosted version of ntfy, you can configure it in your `.env` file:
```dotenv
NTFY_SERVER=https://ntfy.example.com
```

By default, authentication is disabled. If you want to connect using credentials, you can also configure it within you `.env` file:
```dotenv
NTFY_AUTH_ENABLED=true
NTFY_AUTH_USERNAME=michel
NTFY_AUTH_PASSWORD=m0tDeP4ss3
```

To see default values and other settings, see the `config/ntfy-notification-channel.php` config file.

## Usage

In your Notification class, tell Laravel to send your notification via [ntfy](https://ntfy.sh) 
by returning the `NtfyChannel::class` in the `via()` method:

```php
use Wijourdil\NtfyNotificationChannel\Channels\NtfyChannel;

public function via($notifiable)
{
    return [NtfyChannel::class];
}
```

Then, define a `toNtfy()` method in your Notification:

```php
use Ntfy\Message;

public function toNtfy(mixed $notifiable): Message
{
    $message = new Message();
    $message->topic('test');
    $message->title('It works!');
    $message->body("And voila, I sent my notification using ntfy.sh!");
    $message->tags(['white_check_mark', 'ok_hand']);

    return $message;
}
```

Here is what the notification looks like using [the ntfy mobile app](https://ntfy.sh/docs/subscribe/phone/):

![Notification example with ntfy app](notification.png)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

[//]: # (## Contributing)

[//]: # ()
[//]: # (Please see [CONTRIBUTING]&#40;CONTRIBUTING.md&#41; for details.)

[//]: # ()
[//]: # (## Security Vulnerabilities)

[//]: # ()
[//]: # (Please review [our security policy]&#40;../../security/policy&#41; on how to report security vulnerabilities.)

## Credits

- [Wilfried Jourdil](https://github.com/wijourdil)

[//]: # (- [All Contributors]&#40;../../contributors&#41;)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
