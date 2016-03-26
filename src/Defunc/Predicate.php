<?php

namespace Defunc;

class Predicate
{
    protected $retval;

    public function __construct()
    {
        $this->retval = null;
    }

    public function willReturn($retval)
    {
        $this->retval = $retval;
        return $this;
    }

    public function call()
    {
        return $this->retval;
    }
}
