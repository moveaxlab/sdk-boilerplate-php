<?php

namespace SDK\Boilerplate\Validation\Rules;

class Base58Rule implements Rule
{

    const REGEXP = '/^[1-9A-HJ-NP-Za-km-z]+$/';


    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('base58', function($attribute, $value, $parameters, $validator) {

            if(!is_string($value)) return false;

            if (!preg_match(self::REGEXP, $value)) {
                return false;
            }

            return true;

        }, "The :attribute field must be a valid base64 encoded file.");

    }

}