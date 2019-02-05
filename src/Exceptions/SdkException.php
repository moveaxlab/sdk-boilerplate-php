<?php

namespace SDK\Boilerplate\Exceptions;


use Throwable;

class SdkException extends \Exception
{

    /**
     * The error code
     *
     * @var mixed
     */
    protected $errorKey;

    /**
     * SdkException constructor.
     *
     * @param string $message
     * @param mixed $errorKey
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $errorKey, Throwable $previous = null)
    {
        parent::__construct($message, intval($errorKey), $previous);
    }

    public function getErrorKey()
    {
        return $this->errorKey;
    }

}