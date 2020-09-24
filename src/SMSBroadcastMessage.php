<?php

namespace NotificationChannels\SMSBroadcast;

class SMSBroadcastMessage
{
    public $body;
    public $from;
    public $recipients;
    public $reference;
    public $maxSplit;
    public $delay;
    public $privateReference;
    public $noFrom;

    /**
     * Create a new instance statically
     *
     * @param string $body
     * @return void
     */
    public static function create($body = '')
    {
        return new static($body);
    }

    /**
     * Constructor
     *
     * @param string $body
     */
    public function __construct($body = '')
    {
        if (!empty($body)) {
            $this->body = trim($body);
        }
    }

    /**
     * Set the body of the message
     *
     * @param string $body
     * @return void
     */
    public function setBody($body)
    {
        $this->body = trim($body);

        return $this;
    }

    /**
     * Set who the message should come from
     *
     * @param string $from
     * @return void
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set a flag to indicate that there should be no from address by default
     * This will use SMS Broadcast's two way SMS sending number
     *
     * @return void
     */
    public function setNoFrom()
    {
        $this->noFrom = true;

        return $this;
    }

    /**
     * Set the recipients to the message
     *
     * @param string|array $recipients
     * @return void
     */
    public function setRecipients($recipients)
    {
        if (is_array($recipients)) {
            $recipients = implode(',', $recipients);
        }

        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Set a reference for the message which will transmit to SMS Broadcast
     *
     * @param string $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = substr($reference, 0, 20);

        return $this;
    }

    /**
     * Set a private reference for the message that will not transmit to SMS Broadcast and can be used internally on events
     *
     * @param string $privateReference
     * @return $this
     */
    public function setPrivateReference(string $privateReference)
    {
        $this->privateReference = (string) $privateReference;

        return $this;
    }

    /**
     * Set the maximum split of the message (maximum number of SMS credits to use per recipient)
     *
     * @param integer $maxSplit
     * @return $this
     */
    public function setMaxSplit(int $maxSplit = 1)
    {
        $this->maxSplit = (int)$maxSplit;

        return $this;
    }

    /**
     * Set a delay when sending the message in minutes
     *
     * @param integer $delay in minutes
     * @return $this
     */
    public function setDelay(int $delay)
    {
        $this->delay = (int)$delay;

        return $this;
    }
}
