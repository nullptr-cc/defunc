<?php

namespace Defunc;

class Space
{
    protected static $funcs = [];
    protected $ns;
    protected $my_funcs;

    public function __construct($ns)
    {
        $this->ns = $ns;
        $this->my_funcs = new \SplQueue();
    }

    public function __call($func_name, $args)
    {
        if (isset(self::$funcs[$this->ns][$func_name])) {
            return self::$funcs[$this->ns][$func_name]->enable()->with(...$args);
        };

        $this->defineFunction($func_name, $this->ns);
        $func = new Func($func_name);

        self::$funcs[$this->ns][$func_name] = $func;
        $this->my_funcs->enqueue($func);

        return $func->enable()->with(...$args);
    }

    public function clear()
    {
        while (!$this->my_funcs->isEmpty()) {
            $func = $this->my_funcs->dequeue();
            $func->disable();
        };
    }

    public static function call($namespace, $func_name, array $args)
    {
        return self::$funcs[$namespace][$func_name]->call(...$args);
    }

    protected function defineFunction($func_name, $namespace)
    {
        $code = sprintf(
            'namespace %s { function %s(...$args) { return \%s::call("%s", "%s", $args); } }',
            $namespace,
            $func_name,
            self::class,
            $namespace,
            $func_name
        );

        eval($code);

        return true;
    }
}
