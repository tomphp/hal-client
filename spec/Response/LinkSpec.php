<?php

namespace spec\TomPHP\HalClient\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\ResponseFetcher;
use TomPHP\HalClient\Response;

class LinkSpec extends ObjectBehavior
{
    function let(ResponseFetcher $fetcher)
    {
        $this->beConstructedWith($fetcher, 'test_name', 'test_href');
    }

    function it_makes_a_request_to_the_link(ResponseFetcher $fetcher)
    {
        $response = new Response([]);

        $fetcher->get('test_href')->willReturn($response);

        $this->get()->shouldReturn($response);
    }
}
