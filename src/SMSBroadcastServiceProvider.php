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

                $afterSendCallback = function (array $response, SMSBroadcastMessage $message) {
                    event(new Events\MessageWasSent($shortMessage, $response));
                };

                return new SMSBroadcastClient(new Client(), $config['username'], $config['password'], $afterSendCallback);
            });
    }
}
