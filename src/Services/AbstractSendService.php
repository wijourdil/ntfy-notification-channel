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

    protected function isTokenAuth(): bool
    {
        return $this->isAuthEnabled() && config('ntfy-notification-channel.authentication.token') !== null;
    }

    protected function getAuthUsername(): ?string
    {
        $username = config('ntfy-notification-channel.authentication.username');

        if ($this->isAuthEnabled() && ! $this->isTokenAuth()) {
            Assert::string($username, 'ntfy auth username must be a string.');
        } else {
            Assert::nullOrString($username, 'ntfy auth username must be a string.');
        }

        return $username !== null ? (string) $username : null;
    }

    protected function getAuthPassword(): ?string
    {
        $password = config('ntfy-notification-channel.authentication.password');

        if ($this->isAuthEnabled() && ! $this->isTokenAuth()) {
            Assert::string($password, 'ntfy auth password must be a string.');
        } else {
            Assert::nullOrString($password, 'ntfy auth password must be a string.');
        }

        return $password !== null ? (string) $password : null;
    }

    protected function getAuthToken(): ?string
    {
        $token = config('ntfy-notification-channel.authentication.token');

        if ($this->isTokenAuth()) {
            Assert::string($token, 'ntfy auth token must be a string.');
        } else {
            Assert::nullOrString($token, 'ntfy auth token must be a string.');
        }

        return $token !== null ? (string) $token : null;
    }
}
