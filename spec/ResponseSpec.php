<?php

namespace spec\TomPHP\HalClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\ResponseFetcher;

class ResponseSpec extends ObjectBehavior
{
    /** @var Link */
    private $link;

    function let(ResponseFetcher $fetcher) {
        $this->link = new Link($fetcher->getWrappedObject(), 'link1', 'href');
        $this->beConstructedWith(
            ['field1' => 'value1'],
            [$this->link]
        );
    }

    function it_returns_fields_by_name()
    {
        $this->field('field1')->shouldReturn('value1');
    }

    function it_returns_fields_by_magic_method()
    {
        $this->__get('field1')->shouldReturn('value1');
    }

    function it_throws_when_requesting_an_unknown_field()
    {
        $this->shouldThrow(new FieldNotFoundException('unknown'))
             ->duringField('unknown');
    }

    function it_lists_know_links()
    {
        $this->links()->shouldReturn(['link1']);
    }

    function it_gets_link_by_name()
    {
        $this->link('link1')->shouldBeLike($this->link);
    }

    function it_gets_link_by_magic_method(ResponseFetcher $fetcher)
    {
        $this->__get('link1')->shouldBeLike($this->link);
    }

    function it_throws_when_requesting_an_unknown_link()
    {
        $this->shouldThrow(new LinkNotFoundException('unknown'))
             ->duringLink('unknown');
    }
}
