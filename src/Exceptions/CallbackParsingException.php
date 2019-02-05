<?php

namespace SDK\Boilerplate\Exceptions;


use Throwable;

class CallbackParsingException extends SdkException
{

    const CODE = 0002;

    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, self::CODE, $previous);
    }

}