<?php

namespace NotificationChannels\SMSBroadcast\Events;

use NotificationChannels\SMSBroadcast\SMSBroadcastMessage;

/**
 * Class MessageWasSent.
 */
class MessageWasSent
{
    /**
     * The SMS Broadcast message.
     *
     * @var SMSBroadcastMessage
     */
    public $message;

    /**
     * The array response.
     *
     * @var array
     */
    public $response;

    /**
     * MessageWasSent constructor.
     *
     * @param SMSBroadcastMessage     $message
     * @param array                   $response
     */
    public function __construct(SMSBroadcastMessage $message, array $response)
    {
        $this->message = $message;
        $this->response = $response;
    }
}