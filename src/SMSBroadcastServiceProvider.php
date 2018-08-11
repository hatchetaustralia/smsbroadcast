<?php

namespace NotificationChannels\SMSBroadcast;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use NotificationChannels\SMSBroadcast\Exceptions\InvalidConfiguration;

class SMSBroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(SMSBroadcastChannel::class)
            ->needs(SMSBroadcastClient::class)
            ->give(function () {
                $config = config('services.smsbroadcast');

                if (is_null($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                $afterSendCallback = function (SMSBroadcastMessage $message, array $response) {
                    event(new Events\MessageWasSent($message, $response));
                };

                return new SMSBroadcastClient(new Client(), $config['username'], $config['password'], $afterSendCallback, $config['sandbox'] ?? false);
            });
    }
}
