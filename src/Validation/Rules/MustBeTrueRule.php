<?php

namespace SDK\Boilerplate\Validation\Rules;


class MustBeTrueRule implements Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('must_be_true', function($attribute, $value, $parameters, $validator) {

            return $value === true;

        }, "The :attribute field must be true.");

    }

}