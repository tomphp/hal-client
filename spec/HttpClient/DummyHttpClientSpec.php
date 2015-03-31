<?php

namespace spec\TomPHP\HalClient\HttpClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\HttpClient\DummyHttpClient;

class DummyHttpClientSpec extends ObjectBehavior
{
    function it_gets_a_response_from_an_endpoint()
    {
        $this->createEndpoint(
            DummyHttpClient::METHOD_GET,
            'http://api.test.com/',
            'application/hal+xml',
           '{"name":"value"}'
        );

        $response = $this->get('http://api.test.com/');

        $response->getHeader('content-type')->shouldReturn('application/hal+xml');
        $response->getBody()->__toString()->shouldReturn('{"name":"value"}');
    }
}
