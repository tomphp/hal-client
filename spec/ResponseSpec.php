<?php

namespace spec\TomPHP\HalClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseSpec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith(
            ['field1' => 'value1'],
            []
        );
    }

    function it_returns_fields_by_name()
    {
        $this->field('field1')->shouldReturn('value1');
    }

    function it_returns_fields_by_magic_method()
    {
        $this->__get('field1')->shouldReturn('value1');
    }
}
