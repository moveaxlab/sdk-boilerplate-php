<?php

namespace SDK\Boilerplate\Validation\Rules;


class EqualsRule implements Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('equals', function($attribute, $value, $parameters, $validator) {

            return $value === $parameters[0];

        }, "The :attribute field must be equals to :params.");

    }

}