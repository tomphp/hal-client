<?php

namespace spec\TomPHP\HalClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\HttpClient;
use TomPHP\HalClient\HttpResponse;
use TomPHP\HalClient\Exception\UnknownContentTypeException;
use TomPHP\HalClient\Processor;
use TomPHP\HalClient\Resource;

class ClientSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, Processor $processor)
    {
        $processor->getContentType()->willReturn('application/hal+json');
        $processor->process(Argument::any())->willReturn();

        $this->beConstructedWith($httpClient, [
            $processor
        ]);
    }

    function it_throw_if_content_type_is_unknown(HttpClient $httpClient)
    {
        $httpClient->get('http://api.test.com/')
            ->willReturn(new HttpResponse('application/unknown-type', ''));

        $this->shouldThrow(
            new UnknownContentTypeException('application/unknown-type')
        )->duringGet('http://api.test.com/');
    }

    function it_returns_a_processed_resource(HttpClient $httpClient, Processor $processor)
    {
        $httpResource = new HttpResponse('application/hal+json', '');
        $response = new Resource([], []);

        $httpClient->get('http://api.test.com/')->willReturn($httpResource);

        $processor->process($httpResource, $this)->willReturn($response);

        $this->get('http://api.test.com/')->shouldReturn($response);
    }
}
