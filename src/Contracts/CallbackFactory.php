<?php


namespace SDK\Boilerplate\Contracts;


interface CallbackFactory
{

    /**
     * Returns an instance of the callback
     *
     * @param array $headers
     * @param mixed $body
     * @return \SDK\Boilerplate\Callbacks\Callback
     */
    public static function make(array $headers, $body);

}