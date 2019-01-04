<?php

namespace SDK\Boilerplate\Contracts;


interface Factory
{

    /**
     * Make an action
     *
     * @param string $what
     * @return Action
     */
    function make($what);

}