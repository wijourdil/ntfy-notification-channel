<?php

namespace Wijourdil\NtfyNotificationChannel\Tests\DummyClasses\Notifications;

use Illuminate\Notifications\Notification;

abstract class AbstractDummyNotification extends Notification
{
    public function __construct(
        protected readonly string $topic,
        protected readonly string $title,
        protected readonly string $body,
        protected readonly string $priority,
        protected readonly array $tags,
    ) {}
}
