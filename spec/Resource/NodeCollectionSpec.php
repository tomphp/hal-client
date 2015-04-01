<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\Resource\Node;
use TomPHP\HalClient\Exception\MutabilityException;
use stdClass;

class NodeCollectionSpec extends ObjectBehavior
{
    function it_is_accessible_via_ArrayAccess(Node $f1, Node $f2)
    {
        $this->beConstructedWith([$f1, $f2]);

        $this[0]->shouldBe($f1);
        $this[1]->shouldBe($f2);
    }

    function it_is_accessible_can_be_checked_if_index_exists(Node $f1, Node $f2)
    {
        $this->beConstructedWith([$f1, $f2]);

        $this->offsetExists(0)->shouldReturn(true);
        $this->offsetExists(2)->shouldReturn(false);
    }

    function it_is_immutable(Node $f1, Node $f2)
    {
        $this->beConstructedWith([$f1, $f2]);

        $this->shouldThrow(new MutabilityException())->duringOffsetSet(0, 'value');
        $this->shouldThrow(new MutabilityException())->duringOffsetUnset(0);
    }
}
