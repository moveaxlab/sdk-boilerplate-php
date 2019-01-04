<?php

namespace SDK\Boilerplate\Exceptions;


use Throwable;

class CallbackVerificationException extends SdkException
{

    const CODE = 0001;

    public function __construct($message = "", Throwable $previous = null)
    {
        $message = 'Callback verification failed' . ($message ? ': ' . $message : '');
        parent::__construct($message, self::CODE, $previous);
    }

}