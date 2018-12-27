<?php

namespace SDK\Boilerplate\Contracts;

interface Action
{

    const GET = 'GET';
    const POST = 'POST';
    const PATCH = 'PATCH';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';
    const HEAD = 'HEAD';

    /**
     * Define the endpoint route
     *
     * @return string
     */
    static function route();

    /**
     * Define the endpoint verb
     *
     * @return string
     */
    static function verb();

}