<?php

namespace Test\AlexManno\Messagenet\Client;

use AlexManno\Messagenet\Client\MessageNetClient;
use AlexManno\Messagenet\Model\SmsMessage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class MessageNetClientTest extends TestCase
{

    public function testSendSms()
    {
        $clientProp = $this->prophesize(ClientInterface::class);

        $clientProp->send(
            Argument::allOf(
                Argument::type(RequestInterface::class),
                Argument::that(function (RequestInterface $request): bool {
                    $this->assertStringContainsString('send_sms', $request->getUri()->getPath());
                    $this->assertStringContainsString('123', $request->getUri()->getQuery());
                    $this->assertStringContainsString('abc', $request->getUri()->getQuery());
                    $this->assertStringContainsString('012332111', $request->getUri()->getQuery());
                    $this->assertStringContainsString('Example+text', $request->getUri()->getQuery());

                    return true;
                })
            )
        )->shouldBeCalledTimes(
            1
        )->willReturn(
            new Response(200, [], '{}')
        );

        $messageNetClient = new MessageNetClient(
            '123',
            'abc',
            $clientProp->reveal()
        );

        $sms = new SmsMessage(['012332111'], 'Example text');

        $this->assertIsArray($messageNetClient->sendSms($sms));
    }

    public function testSendSmsAsync()
    {
        $clientProp = $this->prophesize(ClientInterface::class);

        $clientProp->sendAsync(
            Argument::allOf(
                Argument::type(RequestInterface::class),
                Argument::that(function (RequestInterface $request): bool {
                    $this->assertStringContainsString('send_sms', $request->getUri()->getPath());
                    $this->assertStringContainsString('123', $request->getUri()->getQuery());
                    $this->assertStringContainsString('abc', $request->getUri()->getQuery());
                    $this->assertStringContainsString('012332111', $request->getUri()->getQuery());
                    $this->assertStringContainsString('Example+text', $request->getUri()->getQuery());

                    return true;
                })
            )
        )->shouldBeCalledTimes(
            1
        )->willReturn(
            new Promise()
        );

        $messageNetClient = new MessageNetClient(
            '123',
            'abc',
            $clientProp->reveal()
        );

        $sms = new SmsMessage(['012332111'], 'Example text');

        $this->assertInstanceOf(PromiseInterface::class, $messageNetClient->sendSmsAsync($sms));
    }
}
