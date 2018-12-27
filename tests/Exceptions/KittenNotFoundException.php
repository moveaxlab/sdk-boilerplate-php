<?php

namespace SDK\Boilerplate\Tests\Exceptions;


use Illuminate\Support\Arr;
use SDK\Boilerplate\Action;
use SDK\Boilerplate\Exceptions\StatusException;

class KittenNotFoundException extends StatusException
{

    const CODE = '0123';

    public function __construct(Action $action)
    {

        $uuid = Arr::get($action->getRouteParameters(), 'uuid', '');

        $message = "Kitten with '$uuid' not found.";

        parent::__construct($action, $message, self::CODE);
    }

}