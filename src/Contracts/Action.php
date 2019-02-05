<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\SdkObject;
use SDK\Boilerplate\SdkObjectCollection;

interface Action
{

    /**
     * Runs the action
     *
     * @return SdkObject|SdkObjectCollection|mixed
     */
    public function run();
}