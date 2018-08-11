<?php

namespace NotificationChannels\SMSBroadcast\Test;

use NotificationChannels\SMSBroadcast\SMSBroadcastMessage;
use PHPUnit\Framework\TestCase;

class SMSBroadcastMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = new SMSBroadcastMessage;

        $this->assertInstanceOf(SMSBroadcastMessage::class, $message);
    }

    /** @test */
    public function it_can_accept_body_content_when_created()
    {
        $message = new SMSBroadcastMessage('Foo');

        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = SMSBroadcastMessage::create('Foo');

        $this->assertInstanceOf(SMSBroadcastMessage::class, $message);
        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = (new SMSBroadcastMessage)->setBody('Bar');

        $this->assertEquals('Bar', $message->body);
    }

    /** @test */
    public function it_can_set_from()
    {
        $message = (new SMSBroadcastMessage)->setFrom('APPNAME');

        $this->assertEquals('APPNAME', $message->from);
    }

    /** @test */
    public function it_can_set_maxsplit()
    {
        $message = (new SMSBroadcastMessage)->setMaxSplit(2);

        $this->assertEquals(2, $message->maxSplit);
    }

    /** @test */
    public function it_can_set_delay()
    {
        $message = (new SMSBroadcastMessage)->setDelay(30);

        $this->assertEquals(30, $message->delay);
    }

    /** @test */
    public function it_can_set_recipients_from_array()
    {
        $message = (new SMSBroadcastMessage)->setRecipients([61411111111, 61411111112]);

        $this->assertEquals('61411111111,61411111112', $message->recipients);
    }

    /** @test */
    public function it_can_set_recipients_from_integer()
    {
        $message = (new SMSBroadcastMessage)->setRecipients(61411111111);

        $this->assertEquals(61411111111, $message->recipients);
    }

    /** @test */
    public function it_can_set_recipients_from_string()
    {
        $message = (new SMSBroadcastMessage)->setRecipients('0411111111');

        $this->assertEquals('0411111111', $message->recipients);
    }
}
