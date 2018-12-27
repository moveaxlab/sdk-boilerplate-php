<?php

namespace SDK\Boilerplate\Validation\Rules;

class HexRule implements Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('hex', function($attribute, $value, $parameters, $validator) {

            return preg_match('/^[A-Fa-f0-9]+$/', $value);

        }, "The :attribute field must be a valid hex string.");

    }

}