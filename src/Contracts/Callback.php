<?php

namespace SDK\Boilerplate\Contracts;


interface Callback
{

    /**
     * Returns the object class
     *
     * @return string
     */
    public static function objectClass();

    /**
     * Return the key used to retrieve the object data
     *
     * @return string
     */
    public static function objectKey();

}