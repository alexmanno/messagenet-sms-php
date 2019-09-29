<?php

declare(strict_types=1);

namespace AlexManno\Messagenet\Client;

use AlexManno\Messagenet\Model\SmsMessage;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use function GuzzleHttp\Promise\settle;

class MessageNetClient
{
    private const BASE_URI = 'https://api.messagenet.com/api/';

    /** @var string */
    private $authUserId;
    /** @var string */
    private $authPassword;
    /** @var Client */
    private $client;

    public function __construct(string $authUserId, string $authPassword, ClientInterface $client = null)
    {
        $this->authUserId = $authUserId;
        $this->authPassword = $authPassword;
        $this->client = $client ?? $this->buildGuzzleClient();
    }

    public function sendSms(SmsMessage $message): array
    {
        $response = $this->client->send($this->buildRequest($message));

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    public function sendSmsAsync(SmsMessage $message): PromiseInterface
    {
        return $this->client->sendAsync($this->buildRequest($message));
    }

    /**
     * @param SmsMessage[]|array $messages
     */
    public function sendSmsBulk(array $messages): PromiseInterface
    {
        $requests = array_map(function (SmsMessage $message): RequestInterface {
            return $this->buildRequest($message);
        }, $messages);

        $promises = array_map(function (RequestInterface $request): PromiseInterface {
            return $this->client->sendAsync($request);
        }, $requests);

        return settle($promises);
    }

    private function buildRequest(SmsMessage $message): RequestInterface
    {
        return new Request(
            'POST',
            'send_sms?' . http_build_query([
                'auth_userid' => $this->authUserId,
                'auth_password' => $this->authPassword,
                'destination' => implode(';', $message->getDestinations()),
                'text' => $message->getText(),
                'format' => 'json',
            ], '', '&')
        );
    }

    private function buildGuzzleClient(): ClientInterface
    {
        return new Client([
            'base_uri' => self::BASE_URI
        ]);
    }
}