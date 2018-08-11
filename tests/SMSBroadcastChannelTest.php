<?php

namespace NotificationChannels\SMSBroadcast\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\SMSBroadcast\SMSBroadcastChannel;
use NotificationChannels\SMSBroadcast\SMSBroadcastClient;
use NotificationChannels\SMSBroadcast\SMSBroadcastMessage;
use PHPUnit\Framework\TestCase;

class SMSBroadcastChannelTest extends TestCase
{
    public function setUp()
    {
        $this->notification = new TestNotification;
        $this->string_notification = new TestStringNotification;
        $this->notifiable = new TestNotifiable;
        $this->guzzle = Mockery::mock(new Client());
        $this->client = Mockery::mock(new SMSBroadcastClient($this->guzzle, 'username', 'password'));
        $this->channel = new SMSBroadcastChannel($this->client);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(SMSBroadcastClient::class, $this->client);
        $this->assertInstanceOf(SMSBroadcastChannel::class, $this->channel);
    }

    /** @test */
    public function test_it_sends_message()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function if_string_message_will_send()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->string_notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForSmsBroadcast()
    {
        return '0411111111';
    }
}

class TestNotification extends Notification
{
    public function toSMSBroadcast($notifiable)
    {
        return (new SMSBroadcastMessage('Message content'))->setFrom('APPNAME')->setRecipients('0411111111');
    }
}

class TestStringNotification extends Notification
{
    public function toSMSBroadcast($notifiable)
    {
        return 'Test by string';
    }
}
