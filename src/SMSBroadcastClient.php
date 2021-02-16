<?php

namespace NotificationChannels\SMSBroadcast;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use NotificationChannels\SMSBroadcast\Exceptions\CouldNotSendNotification;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class SMSBroadcastClient
{
    protected $client;
    protected $username;
    protected $password;
    private $afterSendCallback;
    protected $mock;

    /**
     * Logger instance
     *
     * @var Logger
     */
    private $logger;

    /**
     * SMSBroadcastClient constructor.
     * 
     * @param Client $client
     * @param $username string username for SMS Broadcast
     * @param $password string password for SMS Broadcast
     * @param  callable|null                          $afterSendCallback
     */
    public function __construct(Client $client, $username, $password, $afterSendCallback = null, $mock = false)
    {
        $this->client = $client;
        $this->username = $username;
        $this->password = $password;
        $this->afterSendCallback = $afterSendCallback;
        $this->mock = $mock;
    }

    /**
     * Send the SMS message.
     * 
     * @param SMSBroadcastMessage $message
     * @throws CouldNotSendNotification
     */
    public function send(SMSBroadcastMessage $message)
    {
        app('log')->debug('Sending message');

        // Set the default from
        if (empty($message->from) && !$message->noFrom) {
            $message->setFrom(config('services.smsbroadcast.from'));
            app('log')->debug(sprintf('Set from to %s', $message->from));
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
                'message' => $message->body,
                'maxsplit' => $message->maxSplit,
            ];

            if ($message->delay) {
                $params['delay'] = $message->delay;
            }

            if ($message->reference) {
                $params['ref'] = $message->reference;
            }

            if ($message->from && !$message->noFrom) {
                $params['from'] = $message->from;
            }

            app('log')->debug('Posting params', $params);

            // Send the post request
            if (!$this->mock) {
                $response = $this->client->request('POST', 'https://api.smsbroadcast.com.au/api-adv.php', [
                    'form_params' => $params,
                ]);
                app('log')->debug('Posted to SMS Broadcast');
            } else {
                app('log')->debug('Posted to mock');
            }
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception->getMessage());
        }

        // Check for an error in the response
        if (! $this->mock) {
            $response = (string)$response->getBody();
        } else {
            $recipients = explode(',', $message->recipients);
            $response = "";
            foreach ($recipients as $recipient) {
                $response .= "OK:" . $recipient . ":" . "12345" . rand(100000, 999999) . "\n";
            }
        }

        app('log')->debug('Response: ' . $response);

        // Break up the response lines
        $responseLines = explode("\n", $response);
        $responseData = [];
        foreach( $responseLines as $line) {
            $msgData = "";
            $msgData = explode(':', $line);
            if($msgData[0] == "OK" || $msgData[0] == "BAD") {
                $responseData[] = [
                    'to' => $msgData[1],
                    'status' => $msgData[0],
                    'code' => $msgData[2],
                ];
            } else if( $msgData[0] == "ERROR" ) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($msgData[1]);
            }
        }

        // Call the event
        if (is_callable($this->afterSendCallback)) {
            app('log')->debug('Calling event callback');
            call_user_func_array($this->afterSendCallback, [$message, $responseData]);
        }
    }
}
