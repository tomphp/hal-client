<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use TomPHP\HalClient\Resource\Field;
use TomPHP\HalClient\Exception\FieldNotFoundException;

class FieldMapSpec extends ObjectBehavior
{
    /** @var Field */
    private $field;

    function let()
    {
        $this->field = new Field('testfield');

        $this->beConstructedWith(['fieldname' => $this->field]);
    }

    function it_returns_field_via_field_method()
    {
        $this->field('fieldname')->shouldReturn($this->field);
    }

    function it_throws_if_field_is_not_found()
    {
        $this->shouldThrow(new FieldNotFoundException('unknown-field'))
             ->duringField('unknown-field');
    }

    function it_returns_field_via_magic_method()
    {
        $this->fieldname->shouldReturn($this->field);
    }

    function it_creates_fields_from_object()
    {
        $obj = new stdClass();
        $obj->myfield = 'test value';

        $this->beConstructedThrough('fromObject', [$obj]);

        $this->myfield->value()->shouldReturn('test value');
    }

    function it_creates_field_map_from_object()
    {
        $obj = new stdClass();
        $obj->mymap = new stdClass();
        $obj->mymap->myfield = 'test value';

        $this->beConstructedThrough('fromObject', [$obj]);

        $this->mymap->myfield->value()->shouldReturn('test value');
    }
}
