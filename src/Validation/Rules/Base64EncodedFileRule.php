<?php

namespace SDK\Boilerplate\Validation\Rules;

use SDK\Boilerplate\Utils\Base64FileUtils;

class Base64EncodedFileRule implements Rule
{

    const REGEXP = '/^data:[a-z]+\/[a-z]+;base64,(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/X';


    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('base64_encoded_file', function($attribute, $value, $parameters, $validator) {

            return Base64FileUtils::isValid($value);

        }, "The :attribute field must be a valid base64 encoded file.");

    }

}