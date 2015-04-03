<?php

namespace TomPHP\HalClient\Resource;

interface Matchable
{
    /** @return boolean */
    public function matches($criteria);
}
