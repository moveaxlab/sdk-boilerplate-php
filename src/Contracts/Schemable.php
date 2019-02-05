<?php

namespace SDK\Boilerplate\Contracts;


use ElevenLab\Validation\Spec;

interface Schemable
{

    /**
     * Define the entity schema
     *
     * @return Spec
     */
    static function schema();

}