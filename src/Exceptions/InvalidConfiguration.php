<?php

namespace NotificationChannels\SMSBroadcast\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @return static
     */
    public static function configurationNotSet()
    {
        return new static('In order to send notification via SMSBroadcast you need to add credentials in the `smsbroadcast` key of `config.services`.');
    }
}
