<?php

namespace Wijourdil\NtfyNotificationChannel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wijourdil\NtfyNotificationChannel\NtfyNotificationChannelServiceProvider;
use Wijourdil\NtfyNotificationChannel\Services\AbstractSendService;
use Wijourdil\NtfyNotificationChannel\Services\FakeSendService;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        FakeSendService::reset();

        $this->app->bind(AbstractSendService::class, FakeSendService::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            NtfyNotificationChannelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
