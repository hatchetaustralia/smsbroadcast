<?php

namespace NotificationChannels\SMSBroadcast;

use Exception;
use GuzzleHttp\Client;
use NotificationChannels\SMSBroadcast\Exceptions\CouldNotSendNotification;
use NotificationChannels\SMSBroadcast\Events\MessageWasSent;
use Illuminate\Support\ServiceProvider;

class SMSBroadcastClient
{
    protected $client;
    protected $username;
    protected $password;
    private $afterSendCallback;

    /**
     * SMSBroadcastClient constructor.
     * 
     * @param Client $client
     * @param $username string username for SMS Broadcast
     * @param $password string password for SMS Broadcast
     * @param  callable|null                          $afterSendCallback
     */
    public function __construct(Client $client, $username, $password, $afterSendCallback = null)
    {
        $this->client = $client;
        $this->username = $username;
        $this->password = $password;
        $this->afterSendCallback = $afterSendCallback;
    }

    /**
     * Send the SMS message.
     * 
     * @param SMSBroadcastMessage $message
     * @throws CouldNotSendNotification
     */
    public function send(SMSBroadcastMessage $message)
    {
        // Set the default from
        if (empty($message->from)) {
            $message->setFrom(config('services.smsbroadcast.from'));
        }

        // Set the default maximum split
        if (empty($message->maxSplit)) {
            $message->setMaxSplit(1);
        }

        try {
            // Set the parameters
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to' => $message->recipients,
                'from' => $message->from,
                'message' => urlencode($message->body),
                'maxsplit' => $message->maxSplit,
            ];

            if ($message->delay) {
                $params['delay'] = $message->delay;
            }

            if ($message->reference) {
                $params['reference'] = $message->reference;
            }

            // Send the post request
            $response = $this->client->request('POST', 'https://api.smsbroadcast.com.au/api-adv.php', [
                'form_params' => $params,
            ]);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }

        //dd($response);

        // Parse the response
        $responseData = ['meow'];

        // Call the event
        if (is_callable($this->afterSendCallback)) {
            call_user_func_array($this->afterSendCallback, [$message, $responseData]);
        }
    }
}
