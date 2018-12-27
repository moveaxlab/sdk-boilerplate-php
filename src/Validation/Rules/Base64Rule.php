<?php

namespace SDK\Boilerplate\Validation\Rules;


class Base64Rule implements Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('base64', function($attribute, $value, $parameters, $validator) {

            if(!is_string($value)) return false;

            if (base64_encode(base64_decode($value, true)) === $value) {
                return true;
            } else {
                return false;
            }

        }, "The :attribute field must be a valid base64 encoded string.");

    }

}