<?php

namespace Wijourdil\NtfyNotificationChannel\Channels;

use Illuminate\Notifications\Notification;
use Ntfy\Message;
use Webmozart\Assert\Assert;
use Wijourdil\NtfyNotificationChannel\Services\AbstractSendService;

class NtfyChannel
{
    public function __construct(private readonly AbstractSendService $sender) {}

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        Assert::methodExists($notification, 'toNtfy', 'The method toNtfy() must be defined in the '.Notification::class.' class.');

        $message = $notification->toNtfy($notifiable);

        Assert::isInstanceOf($message, Message::class, 'The toNtfy() method must return a '.Message::class.' object.');

        $this->sender->send($message);
    }
}
