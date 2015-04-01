<?php

namespace TomPHP\HalClient\Resource;

abstract class Node
{
    /**
     * @param mixed
     *
     * @return boolean
     */
    public function matches($critera)
    {
    }
}
