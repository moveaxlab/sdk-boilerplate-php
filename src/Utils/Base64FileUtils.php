<?php

namespace SDK\Boilerplate\Utils;


use Illuminate\Support\Str;

class Base64FileUtils
{

    const VALIDATION_REGEX = '/^data:[a-z]+\/[a-z]+;base64,(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/X';
    const FILE_FORMAT_REGEX = "/^data:[a-z]+\/([a-z]+);base64,.*$/";
    const FILE_TYPE_REGEX = "/^data:([a-z]+)\/[a-z]+;base64,.*$/";
    const FILE_SIZE_REGEX = "/^data:[a-z]+\/[a-z]+;base64,(.*)$/";

    public static function isValid($encodedFile)
    {

        if(!is_string($encodedFile)) return false;

        if (!preg_match(self::VALIDATION_REGEX, $encodedFile)) {
            return false;
        }
        return true;

    }

    public static function format($encodedFile)
    {

        $matches = [];

        if(!self::isValid($encodedFile)) return false;

        preg_match(self::FILE_FORMAT_REGEX, $encodedFile, $matches);

        if(!isset($matches[1])) return false;

        return $matches[1];
    }

    public static function type($encodedFile)
    {

        $matches = [];

        if(!self::isValid($encodedFile)) return false;

        preg_match(self::FILE_TYPE_REGEX, $encodedFile, $matches);

        if(!isset($matches[1])) return false;

        return $matches[1];

    }

    public static function size($encodedFile)
    {

        if(!self::isValid($encodedFile)) return false;

        $matches = [];

        preg_match(self::FILE_SIZE_REGEX, $encodedFile, $matches);

        if(!isset($matches[1])) return false;

        $file = $matches[1];
        $length = (strlen($file) / 4) * 3;

        if(Str::endsWith($file, "===")) $length -= 2;
        else if(Str::endsWith($file, "=")) $length -= 1;

        return $length;

    }

}