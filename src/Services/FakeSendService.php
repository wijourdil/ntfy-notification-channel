<?php

namespace Wijourdil\NtfyNotificationChannel\Services;

use Ntfy\Message;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * @codeCoverageIgnore
 */
class FakeSendService extends AbstractSendService
{
    private static ?Message $lastSentMessage = null;

    /** @var array<string,mixed> */
    private static array $lastSentMessageConfig = [];

    public static function reset(): void
    {
        self::$lastSentMessage = null;
        self::$lastSentMessageConfig = [];
    }

    public static function assertNothingSent(): void
    {
        PHPUnit::assertNull(self::$lastSentMessage);
    }

    public static function assertLastSentMessage(Message $message): void
    {
        PHPUnit::assertNotNull(self::$lastSentMessage);
        PHPUnit::assertEquals(
            self::$lastSentMessage->getData(),
            $message->getData()
        );
    }

    public static function assertLastAttemptConfig(
        string $serverUrl,
        bool $authEnabled,
        ?string $username = null,
        ?string $password = null,
    ): void {
        PHPUnit::assertEquals(self::$lastSentMessageConfig, [
            'server' => $serverUrl,
            'auth' => [
                'enabled' => $authEnabled,
                'username' => $username,
                'password' => $password,
            ],
        ]);
    }

    public function send(Message $message): void
    {
        self::$lastSentMessageConfig = [
            'server' => $this->getServerUrl(),
            'auth' => [
                'enabled' => $this->isAuthEnabled(),
                'username' => $this->getAuthUsername(),
                'password' => $this->getAuthPassword(),
            ],
        ];

        self::$lastSentMessage = $message;
    }
}
