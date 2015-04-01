<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use TomPHP\HalClient\Resource\Field;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Resource\Node;

class FieldMapSpec extends ObjectBehavior
{
    function let(Node $f1, Node $f2)
    {
        $this->beConstructedWith([
            'field1' => $f1,
            'field2' => $f2
        ]);
    }

    function it_returns_field_via_field_method(Node $f1)
    {
        $this->getField('field1')->shouldReturn($f1);
    }

    function it_throws_if_field_is_not_found()
    {
        $this->shouldThrow(new FieldNotFoundException('unknown-field'))
             ->duringGetField('unknown-field');
    }

    function it_returns_field_via_magic_method(Node $f1)
    {
        $this->field1->shouldReturn($f1);
    }

    function it_does_not_match_if_critera_includes_unknown_field()
    {
        $this->matches(['unknown_field' => 'some-value'])->shouldReturn(false);
    }

    function it_does_match_if_all_criteria_fields_match(Node $f1, Node $f2)
    {
        $f1->matches('search-value1')->willReturn(true);
        $f2->matches('search-value2')->willReturn(true);

        $this->matches([
            'field1' => 'search-value1',
            'field2' => 'search-value2'
        ])->shouldReturn(true);
    }

    function it_does_not_match_if_any_criteria_fields_fail_to_match(Node $f1, Node $f2)
    {
        $f1->matches('search-value1')->willReturn(true);
        $f2->matches('search-value2')->willReturn(false);

        $this->matches([
            'field1' => 'search-value1',
            'field2' => 'search-value2'
        ])->shouldReturn(false);
    }
}
