<?php

namespace TomPHP\HalClient\Resource;

interface Matchable
{
    /** @return bool */
    public function matches($criteria);
}
