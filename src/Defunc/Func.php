<?php

namespace Defunc;

class Func
{
    protected $func_name;
    protected $predicates;
    protected $enabled;

    public function __construct($func_name)
    {
        $this->func_name = $func_name;
        $this->predicates = [];
        $this->enabled = false;
    }

    public function enable()
    {
        $this->enabled = true;
        return $this;
    }

    public function disable()
    {
        $this->enabled = false;
        return $this;
    }

    public function call(...$args)
    {
        if (!$this->enabled) {
            return call_user_func('\\' . $this->func_name, ...$args);
        };

        $predicate = $this->findPredicate($args);
        if ($predicate !== null) {
            return $predicate->call();
        };

        return $this->getAnyArgsPredicate()->call();
    }

    public function with(...$args)
    {
        return $this->getPredicate($args);
    }

    protected function getPredicate(array $args)
    {
        $predicate = $this->findPredicate($args);
        if ($predicate !== null) {
            return $predicate;
        };

        $predicate = new Predicate();
        $hash = $this->hashArgs($args);
        $this->predicates[$hash] = $predicate;

        return $predicate;
    }

    protected function findPredicate(array $args)
    {
        $hash = $this->hashArgs($args);
        if (isset($this->predicates[$hash])) {
            return $this->predicates[$hash];
        };

        return null;
    }

    protected function getAnyArgsPredicate()
    {
        $hash = 'xxx';
        if (isset($this->predicates[$hash])) {
            return $this->predicates[$hash];
        };

        $predicate = new Predicate();
        $this->predicates[$hash] = $predicate;

        return $predicate;
    }

    protected function hashArgs(array $args)
    {
        return md5(serialize($args));
    }
}
