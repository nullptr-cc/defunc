<?php

namespace Defunc;

class Builder
{
    protected $spaces;

    public function __construct()
    {
        $this->spaces = [];
    }

    public function in($space)
    {
        if (!array_key_exists($space, $this->spaces)) {
            $this->spaces[$space] = new Space($space);
        };

        return $this->spaces[$space];
    }

    public function clear()
    {
        foreach ($this->spaces as $space) {
            $space->clear();
        };
    }
}
