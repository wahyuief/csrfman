<?php

namespace wahyuief\csrfman;

class CSRF
{
    public static $_instance = null;

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new \wahyuief\csrfman\Csrfman();
        }
        return static::$_instance;
    }

    public static function __callStatic($name, $arguments)
    {
        return static::instance()->{$name}(...$arguments);
    }
}