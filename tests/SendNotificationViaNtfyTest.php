<?php

namespace Wijourdil\NtfyNotificationChannel\Tests;

use Ntfy\Message;
use PHPUnit\Framework\Attributes\DataProvider;
use Webmozart\Assert\InvalidArgumentException;
use Wijourdil\NtfyNotificationChannel\Channels\NtfyChannel;
use Wijourdil\NtfyNotificationChannel\Services\FakeSendService;
use Wijourdil\NtfyNotificationChannel\Tests\DummyClasses\DummyNotifiable;
use Wijourdil\NtfyNotificationChannel\Tests\DummyClasses\Notifications\DummyWorkingNotification;
use Wijourdil\NtfyNotificationChannel\Tests\DummyClasses\Notifications\DummyWrongNotificationWithoutMethod;
use Wijourdil\NtfyNotificationChannel\Tests\DummyClasses\Notifications\DummyWrongNotificationWithWrongReturnType;

class SendNotificationViaNtfyTest extends TestCase
{
    private NtfyChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = app(NtfyChannel::class);
    }

    public function test_it_can_send_ntfy_notification_with_auth_if_config_is_ok()
    {
        $this->app->config['ntfy-notification-channel'] = [
            'server' => '127.0.0.1',
            'authentication' => [
                'enabled' => true,
                'username' => 'michel',
                'password' => 'weakpwd',
            ],
        ];

        $notifiable = new DummyNotifiable;
        $notification = new DummyWorkingNotification(
            topic: 'test',
            title: 'My Notification',
            body: 'Blah blah blah',
            priority: Message::PRIORITY_LOW,
            tags: ['warning']
        );

        $this->channel->send(
            $notifiable,
            $notification
        );

        FakeSendService::assertLastSentMessage($notification->toNtfy($notifiable));
        FakeSendService::assertLastAttemptConfig(
            serverUrl: '127.0.0.1',
            authEnabled: true,
            username: 'michel',
            password: 'weakpwd',
        );
    }

    public function test_it_can_send_ntfy_notification_without_auth_if_config_is_ok()
    {
        $this->app->config['ntfy-notification-channel'] = [
            'server' => '127.0.0.1',
            'authentication' => [
                'enabled' => false,
                'username' => null,
                'password' => null,
            ],
        ];

        $notifiable = new DummyNotifiable;
        $notification = new DummyWorkingNotification(
            topic: 'test',
            title: 'My Notification',
            body: 'Blah blah blah',
            priority: Message::PRIORITY_LOW,
            tags: ['warning']
        );

        $this->channel->send(
            $notifiable,
            $notification
        );

        FakeSendService::assertLastSentMessage($notification->toNtfy($notifiable));
        FakeSendService::assertLastAttemptConfig(
            serverUrl: '127.0.0.1',
            authEnabled: false,
            username: null,
            password: null,
        );
    }

    public function test_it_cannot_send_ntfy_notification_if_notification_does_not_implement_to_ntfy_method()
    {
        $this->app->config['ntfy-notification-channel'] = [
            'server' => '127.0.0.1',
            'authentication' => [
                'enabled' => false,
                'username' => null,
                'password' => null,
            ],
        ];

        $notifiable = new DummyNotifiable;
        $notification = new DummyWrongNotificationWithoutMethod(
            topic: 'test',
            title: 'My Notification',
            body: 'Blah blah blah',
            priority: Message::PRIORITY_LOW,
            tags: ['warning']
        );

        $this->expectException(InvalidArgumentException::class);

        $this->channel->send(
            $notifiable,
            $notification
        );

        FakeSendService::assertNothingSent();
    }

    public function test_it_cannot_send_ntfy_notification_if_notification_method_to_ntfy_does_not_return_right_type()
    {
        $this->app->config['ntfy-notification-channel'] = [
            'server' => '127.0.0.1',
            'authentication' => [
                'enabled' => false,
                'username' => null,
                'password' => null,
            ],
        ];

        $notifiable = new DummyNotifiable;
        $notification = new DummyWrongNotificationWithWrongReturnType(
            topic: 'test',
            title: 'My Notification',
            body: 'Blah blah blah',
            priority: Message::PRIORITY_LOW,
            tags: ['warning']
        );

        $this->expectException(InvalidArgumentException::class);

        $this->channel->send(
            $notifiable,
            $notification
        );

        FakeSendService::assertNothingSent();
    }

    #[DataProvider('wrongConfigurationDataProvider')]
    public function test_it_cannot_sent_ntfy_notification_if_config_is_not_valid(array $configuration)
    {
        $this->app->config['ntfy-notification-channel'] = $configuration;

        $notifiable = new DummyNotifiable;
        $notification = new DummyWorkingNotification(
            topic: 'test',
            title: 'My Notification',
            body: 'Blah blah blah',
            priority: Message::PRIORITY_LOW,
            tags: ['warning']
        );

        $this->expectException(InvalidArgumentException::class);

        $this->channel->send(
            $notifiable,
            $notification
        );

        FakeSendService::assertNothingSent();
    }

    public static function wrongConfigurationDataProvider(): array
    {
        return [
            [
                'configuration' => [],
            ],
            [
                'configuration' => [
                    [
                        'server' => '127.0.0.1',
                        'authentication' => [
                            'enabled' => true,
                            'username' => null,
                            'password' => null,
                        ],
                    ],
                ],
            ],
            [
                'configuration' => [
                    [
                        'server' => '127.0.0.1',
                        'authentication' => [
                            'enabled' => true,
                            'username' => 'test',
                            'password' => null,
                        ],
                    ],
                ],
            ],
            [
                'configuration' => [
                    [
                        'server' => '127.0.0.1',
                        'authentication' => [
                            'enabled' => true,
                            'username' => null,
                            'password' => 'test',
                        ],
                    ],
                ],
            ],
            [
                'configuration' => [
                    [
                        'server' => null,
                        'authentication' => [
                            'enabled' => true,
                            'username' => 'michel',
                            'password' => 'test',
                        ],
                    ],
                ],
            ],
        ];
    }
}
