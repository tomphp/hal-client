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
        $this->getField('fieldname')->shouldReturn($this->field);
    }

    function it_throws_if_field_is_not_found()
    {
        $this->shouldThrow(new FieldNotFoundException('unknown-field'))
             ->duringGetField('unknown-field');
    }

    function it_returns_field_via_magic_method()
    {
        $this->fieldname->shouldReturn($this->field);
    }
}
