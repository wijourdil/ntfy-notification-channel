<?php

namespace Wijourdil\NtfyNotificationChannel\Services;

use Ntfy\Auth\Token;
use Ntfy\Auth\User;
use Ntfy\Client;
use Ntfy\Message;
use Ntfy\Server;

class NtfySendService extends AbstractSendService
{
    public function send(Message $message): void
    {
        if ($this->isAuthEnabled()
            && $this->getAuthToken() !== null
        ){
            $auth = new Token($this->getAuthToken());
        }

        if ($this->isAuthEnabled()
            && $this->getAuthToken() == null
            && $this->getAuthUsername() !== null
            && $this->getAuthPassword() !== null
        ){
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
