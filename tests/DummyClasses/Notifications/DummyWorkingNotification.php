<?php

namespace Wijourdil\NtfyNotificationChannel\Tests\DummyClasses\Notifications;

use Ntfy\Message;

class DummyWorkingNotification extends AbstractDummyNotification
{
    public function toNtfy($notifiable): Message
    {
        $message = new Message();

        $message->topic($this->topic);
        $message->body($this->body);
        $message->title($this->title);
        $message->tags($this->tags);
        $message->priority($this->priority);

        return $message;
    }
}
