<?php

namespace NotificationChannels\SMSBroadcast\Test;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Mockery;
use NotificationChannels\SMSBroadcast\SMSBroadcastClient;
use NotificationChannels\SMSBroadcast\SMSBroadcastMessage;
use PHPUnit\Framework\TestCase;

class SMSBroadcastClientTest extends TestCase
{
    protected function setUp(): void
    {
        Log::swap(new LogFake());
        $this->guzzle = Mockery::mock(new Client());
        $this->client = Mockery::mock(new SMSBroadcastClient($this->guzzle, 'username', 'password', null, true));
        $this->message = (new SMSBroadcastMessage('Message content'))->setFrom('APPNAME')->setRecipients('0411111111')->setReference('000123');
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $this->assertInstanceOf(SMSBroadcastClient::class, $this->client);
        $this->assertInstanceOf(SMSBroadcastMessage::class, $this->message);
    }

    /** @test */
    public function it_can_send_message(): void
    {
        $this->client->shouldReceive('send')->once();
        $this->assertNull($this->client->send($this->message));
    }
}
