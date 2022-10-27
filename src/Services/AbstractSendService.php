<?php

namespace Wijourdil\NtfyNotificationChannel\Services;

use Ntfy\Message;
use Webmozart\Assert\Assert;

abstract class AbstractSendService
{
    abstract public function send(Message $message): void;

    protected function getServerUrl(): string
    {
        $serverUrl = config('ntfy-notification-channel.server');

        Assert::string($serverUrl, 'ntfy server url must be a string.');

        return $serverUrl;
    }

    protected function isAuthEnabled(): bool
    {
        return (bool) config('ntfy-notification-channel.authentication.enabled');
    }

    protected function getAuthUsername(): string
    {
        $username = config('ntfy-notification-channel.authentication.username');

        if ($this->isAuthEnabled()) {
            Assert::string($username, 'ntfy auth username must be a string.');
        } else {
            Assert::nullOrString($username, 'ntfy auth username must be a string.');
        }

        return (string) $username;
    }

    protected function getAuthPassword(): string
    {
        $password = config('ntfy-notification-channel.authentication.password');

        if ($this->isAuthEnabled()) {
            Assert::string($password, 'ntfy auth password must be a string.');
        } else {
            Assert::nullOrString($password, 'ntfy auth password must be a string.');
        }

        return (string) $password;
    }
}
