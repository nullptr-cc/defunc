<?php

set_include_path(
    get_include_path() .
    PATH_SEPARATOR .
    realpath(__DIR__ . '/../src')
);

spl_autoload_register(function ($classname) {
    $filename = str_replace('\\', '/', $classname) . '.php';
    if ($realpath = stream_resolve_include_path($filename)) {
        include_once $realpath;
    } else {
        return false;
    };
});
