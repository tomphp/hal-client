<?php

namespace spec\TomPHP\HalClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\Resource\Link;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\ResourceFetcher;
use TomPHP\HalClient\Resource\Field;
use TomPHP\HalClient\Resource;
use TomPHP\HalClient\Exception\ResourceNotFoundException;

class ResourceSpec extends ObjectBehavior
{
    /** @var Field */
    private $field;

    /** @var Link */
    private $link;

    /** @var Resource */
    private $resource;

    function let(ResourceFetcher $fetcher) {
        $this->field    = new Field('value1');
        $this->link     = new Link($fetcher->getWrappedObject(), 'href');
        $this->resource = new Resource([]);

        $this->beConstructedWith(
            ['field1' => $this->field],
            ['link1' => $this->link],
            ['resource1' => $this->resource]
        );
    }

    function it_returns_fields_by_name()
    {
        $this->getField('field1')->shouldBeLike($this->field);
    }

    function it_returns_fields_by_magic_method()
    {
        $this->field1->shouldReturn($this->field);
    }

    function it_throws_when_requesting_an_unknown_field()
    {
        $this->shouldThrow(new FieldNotFoundException('unknown'))
             ->duringGetField('unknown');
    }

    function it_lists_links()
    {
        $this->getLinks()->shouldReturn(['link1']);
    }

    function it_gets_link_by_name()
    {
        $this->getLink('link1')->shouldBeLike($this->link);
    }

    function it_gets_link_by_magic_method()
    {
        $this->link1->shouldBeLike($this->link);
    }

    function it_throws_when_requesting_an_unknown_link()
    {
        $this->shouldThrow(new LinkNotFoundException('unknown'))
             ->duringGetLink('unknown');
    }

    function it_gets_resource_by_name()
    {
        $this->getResource('resource1')->shouldReturn($this->resource);
    }

    function it_throws_when_requesting_an_unknown_resource()
    {
        $this->shouldThrow(new ResourceNotFoundException('unknown'))
             ->duringGetResource('unknown');
    }

    function it_gets_resource_by_magic_method()
    {
        $this->resource1->shouldBeLike($this->resource);
    }
}
