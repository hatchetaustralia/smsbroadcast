<?php

namespace NotificationChannels\SMSBroadcast\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * @param string $exception
     * @return static
     */
    public static function serviceRespondedWithAnError($exception)
    {
        return new static("SMS Broadcast service responded with an error: {$exception}'");
    }
}
