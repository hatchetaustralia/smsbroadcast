<?php

namespace NotificationChannels\SMSBroadcast;

use Illuminate\Notifications\Notification;

class SMSBroadcastChannel
{
    /** 
     * @var \NotificationChannels\SMSBroadcast\SMSBroadcastClient 
     */
    protected $client;

    /**
     * Constructor method
     *
     * @param SMSBroadcastClient $client
     */
    public function __construct(SMSBroadcastClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\SMSBroadcast\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSMSBroadcast($notifiable);

        if (is_string($message)) {
            $message = SMSBroadcastMessage::create($message);
        }

        if ($to = $notifiable->routeNotificationFor('sms broadcast')) {
            $message->setRecipients($to);
        }

        $this->client->send($message);
    }
}
