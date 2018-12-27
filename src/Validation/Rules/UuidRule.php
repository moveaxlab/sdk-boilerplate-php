<?php

namespace SDK\Boilerplate\Validation\Rules;


class UuidRule implements Rule
{

    const REGEX = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/xi';

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('uuid', function($attribute, $value, $parameters, $validator) {

            return preg_match(self::REGEX, $value);

        }, "The :attribute field must be a valid UUID string.");

    }

}