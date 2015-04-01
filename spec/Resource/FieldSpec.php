<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FieldSpec extends ObjectBehavior
{
    function it_matches_critera()
    {
        $this->beConstructedWith('test-value');

        $this->matches('test-value')->shouldReturn(true);
        $this->matches('different-value')->shouldReturn(false);
    }
}
