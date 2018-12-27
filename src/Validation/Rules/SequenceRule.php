<?php

namespace SDK\Boilerplate\Validation\Rules;


class SequenceRule implements Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('sequence', function($attribute, $value, $parameters, $validator) {

            return is_array($value) || is_string($value);

        }, "The :attribute field must be a valid sequence.");

    }

}