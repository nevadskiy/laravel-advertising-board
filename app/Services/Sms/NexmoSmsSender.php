<?php

namespace App\Services\Sms;

use GuzzleHttp\Client;

class NexmoSmsSender implements SmsSender
{
    protected $apiKey;
    protected $apiSecret;
    protected $client;
    protected $url;

    public function __construct(string $apiKey, string $apiSecret, string $url = 'https://rest.nexmo.com/sms/json')
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->url = $url;
        $this->client = new Client();
    }

    public function send($number, $text): void
    {
        $this->client->post($this->url, [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'to' => '+' . $number,
            'from' => config('app.name'),
            'text' => $text,
        ]);
    }
}
