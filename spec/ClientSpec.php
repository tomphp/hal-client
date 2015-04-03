<?php

namespace spec\TomPHP\HalClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use TomPHP\HalClient\Exception\UnknownContentTypeException;
use TomPHP\HalClient\HttpClient;
use TomPHP\HalClient\Processor;
use TomPHP\HalClient\Resource\Resource;

class ClientSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, Processor $processor)
    {
        $processor->getContentType()->willReturn('application/hal+json');
        $processor->process(Argument::any())->willReturn();

        $this->beConstructedWith($httpClient, [$processor]);
    }

    function it_throw_if_content_type_is_unknown(HttpClient $httpClient, ResponseInterface $response)
    {
        $response->getHeader('content-type')->willReturn('application/unknown-type');
        $httpClient->get('http://api.test.com/')->willReturn($response);

        $this->shouldThrow(
            new UnknownContentTypeException('application/unknown-type')
        )->duringGet('http://api.test.com/');
    }

    function it_returns_a_processed_resource(ResponseInterface $response, HttpClient $httpClient, Processor $processor)
    {
        $response->getHeader('content-type')->willReturn('application/hal+json');
        $resource = new Resource([], []);

        $httpClient->get('http://api.test.com/')->willReturn($response);

        $processor->process($response, $this)->willReturn($resource);

        $this->get('http://api.test.com/')->shouldReturn($resource);
    }
}
