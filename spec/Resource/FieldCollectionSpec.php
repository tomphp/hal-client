<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\Resource\FieldNode;
use TomPHP\HalClient\Exception\MutabilityException;
use stdClass;

class FieldCollectionSpec extends ObjectBehavior
{
    function it_is_accessible_via_ArrayAccess(FieldNode $f1, FieldNode $f2)
    {
        $this->beConstructedWith([$f1, $f2]);

        $this[0]->shouldBe($f1);
        $this[1]->shouldBe($f2);
    }

    function it_is_accessible_can_be_checked_if_index_exists(FieldNode $f1, FieldNode $f2)
    {
        $this->beConstructedWith([$f1, $f2]);

        $this->offsetExists(0)->shouldReturn(true);
        $this->offsetExists(2)->shouldReturn(false);
    }

    function it_is_immutable(FieldNode $f1, FieldNode $f2)
    {
        $this->beConstructedWith([$f1, $f2]);

        $this->shouldThrow(new MutabilityException())->duringOffsetSet(0, 'value');
        $this->shouldThrow(new MutabilityException())->duringOffsetUnset(0);
    }

    function it_can_be_built_from_an_array()
    {
        $object = new stdClass();
        $object->field1 = 'value1';

        $values = [$object];

        $this->beConstructedThrough('fromArray', [$values]);

        $this[0]->field1->value()->shouldBe('value1');
    }
}
