<?php

namespace Framework\Http\Pipeline;

class UnknownMiddlewareTypeException extends \Exception
{
    public function __construct(private $type)
    {
        parent::__construct('Unknown middleware type');
    }

    public function getType()
    {
        return $this->type;
    }
}
