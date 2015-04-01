<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use TomPHP\HalClient\ResourceFetcher;
use TomPHP\HalClient\Resource\Resource;

class LinkSpec extends ObjectBehavior
{
    function let(ResourceFetcher $fetcher)
    {
        $this->beConstructedWith($fetcher, 'test_href');
    }

    function it_makes_a_request_to_the_link(ResourceFetcher $fetcher)
    {
        $resource = new Resource([]);

        $fetcher->get('test_href')->willReturn($resource);

        $this->get()->shouldReturn($resource);
    }
}
