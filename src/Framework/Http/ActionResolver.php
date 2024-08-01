<?php

namespace Framework\Http;

class ActionResolver
{
    public static function resolve($handler): callable
    {
        return \is_string($handler) ? new $handler : $handler;
    }
}
