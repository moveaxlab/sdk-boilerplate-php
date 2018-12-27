<?php

namespace SDK\Boilerplate\Contracts;


interface Factory
{

    /**
     * Defines the name => action mapping
     *
     * @return array
     */
    static function actions();

    /**
     * Make an action
     *
     * @param string $what
     * @return Action
     */
    function make($what);

}