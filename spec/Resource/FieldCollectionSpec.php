<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use TomPHP\HalClient\Exception\MutabilityException;
use TomPHP\HalClient\Resource\FieldCollection;
use TomPHP\HalClient\Resource\FieldNode;

class FieldCollectionSpec extends ObjectBehavior
{
    function let(FieldNode $f1, FieldNode $f2)
    {
        $this->beConstructedWith([$f1, $f2]);
    }

    function it_is_accessible_via_ArrayAccess(FieldNode $f1, FieldNode $f2)
    {
        $this[0]->shouldBe($f1);
        $this[1]->shouldBe($f2);
    }

    function it_is_accessible_can_be_checked_if_index_exists(FieldNode $f1, FieldNode $f2)
    {
        $this->offsetExists(0)->shouldReturn(true);
        $this->offsetExists(2)->shouldReturn(false);
    }

    function it_is_immutable(FieldNode $f1, FieldNode $f2)
    {
        $this->shouldThrow(new MutabilityException())->duringOffsetSet(0, 'value');
        $this->shouldThrow(new MutabilityException())->duringOffsetUnset(0);
    }

    function it_finds_matching_nodes(FieldNode $f1, FieldNode $f2)
    {
        $criteria = ['search' => 'criteria'];

        $f1->matches($criteria)->willReturn(false);
        $f2->matches($criteria)->willReturn(true);

        $this->findMatching($criteria)->shouldBeLike(new FieldCollection([$f2->getWrappedObject()]));
    }

    function it_is_countable()
    {
        $this->count()->shouldReturn(2);
    }
}
