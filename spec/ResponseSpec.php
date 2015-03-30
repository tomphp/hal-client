<?php

namespace spec\TomPHP\HalClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\ResponseFetcher;
use TomPHP\HalClient\Response\Field;
use TomPHP\HalClient\Response;
use TomPHP\HalClient\Exception\ResourceNotFoundException;

class ResponseSpec extends ObjectBehavior
{
    /** @var Field */
    private $field;

    /** @var Link */
    private $link;

    /** @var Response */
    private $response;

    function let(ResponseFetcher $fetcher) {
        $this->field    = new Field('field1', 'value1');
        $this->link     = new Link($fetcher->getWrappedObject(), 'link1', 'href');
        $this->response = new Response([]);

        $this->beConstructedWith(
            [$this->field],
            [$this->link],
            ['resource1' => $this->response]
        );
    }

    function it_returns_fields_by_name()
    {
        $this->field('field1')->shouldBeLike($this->field);
    }

    function it_returns_fields_by_magic_method()
    {
        $this->field1->shouldReturn($this->field);
    }

    function it_throws_when_requesting_an_unknown_field()
    {
        $this->shouldThrow(new FieldNotFoundException('unknown'))
             ->duringField('unknown');
    }

    function it_lists_links()
    {
        $this->links()->shouldReturn(['link1']);
    }

    function it_gets_link_by_name()
    {
        $this->link('link1')->shouldBeLike($this->link);
    }

    function it_gets_link_by_magic_method()
    {
        $this->link1->shouldBeLike($this->link);
    }

    function it_throws_when_requesting_an_unknown_link()
    {
        $this->shouldThrow(new LinkNotFoundException('unknown'))
             ->duringLink('unknown');
    }

    function it_gets_resource_by_name()
    {
        $this->resource('resource1')->shouldReturn($this->response);
    }

    function it_throws_when_requesting_an_unknown_resource()
    {
        $this->shouldThrow(new ResourceNotFoundException('unknown'))
             ->duringResource('unknown');
    }

    function it_gets_resource_by_magic_method()
    {
        $this->resource1->shouldBeLike($this->response);
    }
}
