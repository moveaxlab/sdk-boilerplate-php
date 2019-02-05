<?php

namespace SDK\Boilerplate\Contracts;


interface Factory
{

    /**
     * Make an action
     *
     * @param array $parameters
     * @param mixed $what
     * @return mixed
     */
    function make($what = null, ...$parameters);

}