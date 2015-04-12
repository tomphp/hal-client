<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\Exception\ResourceNotFoundException;
use TomPHP\HalClient\ResourceFetcher;
use TomPHP\HalClient\Resource\FieldNode;
use TomPHP\HalClient\Resource\Link;
use TomPHP\HalClient\Resource\ResourceNode;

class ResourceSpec extends ObjectBehavior
{
    /** @var Link */
    private $link;

    function let(ResourceFetcher $fetcher, FieldNode $f1, FieldNode $f2, ResourceNode $resource)
    {
        $this->link = new Link($fetcher->getWrappedObject(), 'href');

        $this->beConstructedWith(
            [
                'field1' => $f1->getWrappedObject(),
                'field2' => $f2->getWrappedObject(),
            ],
            ['link1' => $this->link],
            ['resource1' => $resource->getWrappedObject()]
        );
    }

    function it_returns_fields_by_name(FieldNode $f1)
    {
        $this->getField('field1')->shouldBeLike($f1);
    }

    function it_returns_fields_by_magic_method(FieldNode $f1)
    {
        $this->field1->shouldReturn($f1);
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

    function it_throws_when_requesting_an_unknown_link()
    {
        $this->shouldThrow(new LinkNotFoundException('unknown'))
             ->duringGetLink('unknown');
    }

    function it_gets_resource_by_name(ResourceNode $resource)
    {
        $this->getResource('resource1')->shouldReturn($resource);
    }

    function it_throws_when_requesting_an_unknown_resource()
    {
        $this->shouldThrow(new ResourceNotFoundException('unknown'))
             ->duringGetResource('unknown');
    }

    function it_does_not_match_if_critera_includes_unknown_field()
    {
        $this->matches(['unknown_field' => 'some-value'])->shouldReturn(false);
    }

    function it_does_match_if_all_criteria_fields_match(FieldNode $f1, FieldNode $f2)
    {
        $f1->matches('search-value1')->willReturn(true);
        $f2->matches('search-value2')->willReturn(true);

        $this->matches([
            'field1' => 'search-value1',
            'field2' => 'search-value2',
        ])->shouldReturn(true);
    }

    function it_does_not_match_if_any_criteria_fields_fail_to_match(FieldNode $f1, FieldNode $f2)
    {
        $f1->matches('search-value1')->willReturn(true);
        $f2->matches('search-value2')->willReturn(false);

        $this->matches([
            'field1' => 'search-value1',
            'field2' => 'search-value2',
        ])->shouldReturn(false);
    }

    function it_does_not_match_unknown_resource()
    {
        $this->matches([
            'field1' => 'search-value1',
            ['resource', 'unknown', ['resource_field' => 'search-value2']],
        ])->shouldReturn(false);
    }

    function it_matches_if_resource_matches(FieldNode $f1, ResourceNode $resource)
    {
        $f1->matches('search-value1')->willReturn(true);
        $resource->matches(['resource_field' => 'search-value2'])->willReturn(true);

        $this->matches([
            'field1' => 'search-value1',
            ['resource', 'resource1', ['resource_field' => 'search-value2']],
        ])->shouldReturn(true);
    }

    function it_does_not_match_if_resource_does_not_match(FieldNode $f1, ResourceNode $resource)
    {
        $f1->matches('search-value1')->willReturn(true);
        $resource->matches(['resource_field' => 'search-value2'])->willReturn(false);

        $this->matches([
            'field1' => 'search-value1',
            ['resource', 'resource1', ['resource_field' => 'search-value2']],
        ])->shouldReturn(false);
    }
}
