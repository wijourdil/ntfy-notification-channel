<?php

namespace Wijourdil\NtfyNotificationChannel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wijourdil\NtfyNotificationChannel\Services\AbstractSendService;
use Wijourdil\NtfyNotificationChannel\Services\NtfySendService;

class NtfyNotificationChannelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ntfy-notification-channel')
            ->hasConfigFile();

        $this->app->bind(AbstractSendService::class, NtfySendService::class);
    }
}
