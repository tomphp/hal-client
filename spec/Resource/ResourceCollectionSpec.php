<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use TomPHP\HalClient\Resource\ResourceNode;
use TomPHP\HalClient\Exception\MutabilityException;
use TomPHP\HalClient\Resource\ResourceCollection;

class ResourceCollectionSpec extends ObjectBehavior
{
    function let(ResourceNode $f1, ResourceNode $f2)
    {
        $this->beConstructedWith([$f1, $f2]);
    }

    function it_is_accessible_via_ArrayAccess(ResourceNode $f1, ResourceNode $f2)
    {
        $this[0]->shouldBe($f1);
        $this[1]->shouldBe($f2);
    }

    function it_is_accessible_can_be_checked_if_index_exists(ResourceNode $f1, ResourceNode $f2)
    {
        $this->offsetExists(0)->shouldReturn(true);
        $this->offsetExists(2)->shouldReturn(false);
    }

    function it_is_immutable(ResourceNode $f1, ResourceNode $f2)
    {
        $this->shouldThrow(new MutabilityException())->duringOffsetSet(0, 'value');
        $this->shouldThrow(new MutabilityException())->duringOffsetUnset(0);
    }

    function it_finds_matching_nodes(ResourceNode $f1, ResourceNode $f2)
    {
        $criteria = ['search' => 'criteria'];

        $f1->matches($criteria)->willReturn(false);
        $f2->matches($criteria)->willReturn(true);

        $this->findMatching($criteria)->shouldBeLike(new ResourceCollection([$f2->getWrappedObject()]));
    }

    function it_is_countable()
    {
        $this->count()->shouldReturn(2);
    }
}
