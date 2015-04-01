<?php

namespace TomPHP\HalClient\Resource;

abstract class Node
{
    /**
     * @param array|scalar
     *
     * @return boolean
     */
    public function matches($critera)
    {
    }
}
