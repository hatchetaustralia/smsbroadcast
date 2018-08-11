<?php

namespace NotificationChannels\SMSBroadcast\Test;

use GuzzleHttp\Client;
use Mockery;
use NotificationChannels\SMSBroadcast\SMSBroadcastClient;
use NotificationChannels\SMSBroadcast\SMSBroadcastMessage;
use PHPUnit\Framework\TestCase;

class SMSBroadcastClientTest extends TestCase
{
    public function setUp()
    {
        $this->guzzle = Mockery::mock(new Client());
        $this->client = Mockery::mock(new SMSBroadcastClient($this->guzzle, 'username', 'password', true));
        $this->message = (new SMSBroadcastMessage('Message content'))->setFrom('APPNAME')->setRecipients('0411111111')->setReference('000123');
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
        $this->assertInstanceOf(SMSBroadcastMessage::class, $this->message);
    }

    /** @test */
    public function it_can_send_message()
    {
        $this->client->send($this->message);
    }
}
