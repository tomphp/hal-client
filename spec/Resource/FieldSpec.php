<?php

namespace spec\TomPHP\HalClient\Resource;

use PhpSpec\ObjectBehavior;

class FieldSpec extends ObjectBehavior
{
    function it_matches_critera()
    {
        $this->beConstructedWith('test-value');

        $this->matches('test-value')->shouldReturn(true);
        $this->matches('different-value')->shouldReturn(false);
    }
}
