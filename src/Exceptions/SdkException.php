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
     * Debug infos
     *
     * @var string
     */
    protected $debugInfo;

    /**
     * SdkException constructor.
     *
     * @param string $message
     * @param mixed $errorKey
     * @param Throwable|null $previous
     */
    public function __construct($message, $errorKey, Throwable $previous = null)
    {
        parent::__construct($message, intval($errorKey), $previous);
    }

    /**
     * Returns the debug info about the exception
     *
     * @return string
     */
    public function getDebugInfo() {
        return $this->debugInfo;
    }

    /**
     * Set the exception debug details
     *
     * @param string $debugInfo
     */
    public function setDebugInfo($debugInfo) {
        $this->debugInfo = $debugInfo;
    }

    public function getErrorKey()
    {
        return $this->errorKey;
    }

}