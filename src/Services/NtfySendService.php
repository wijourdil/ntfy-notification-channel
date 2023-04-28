<?php

namespace Wijourdil\NtfyNotificationChannel\Services;

use Ntfy\Auth\User;
use Ntfy\Client;
use Ntfy\Message;
use Ntfy\Server;

class NtfySendService extends AbstractSendService
{
    public function send(Message $message): void
    {
        if ($this->isAuthEnabled()) {
            $auth = new User(
                $this->getAuthUsername(),
                $this->getAuthPassword()
            );
        }

        $client = new Client(
            new Server($this->getServerUrl()),
            $auth ?? null
        );

        $client->send($message);
    }
}
