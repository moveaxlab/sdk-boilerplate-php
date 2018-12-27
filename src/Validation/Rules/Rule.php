<?php

namespace SDK\Boilerplate\Validation\Rules;

interface Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation);

}