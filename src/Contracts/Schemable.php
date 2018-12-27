<?php

namespace SDK\Boilerplate\Contracts;


use SDK\Boilerplate\Validation\Spec;

interface Schemable
{

    /**
     * Define the entity schema
     *
     * @return Spec
     */
    static function schema();

}