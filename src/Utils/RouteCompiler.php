<?php

namespace SDK\Boilerplate\Utils;

use Illuminate\Support\Str;

class RouteCompiler
{

    public static function compile(z$path, $params)
    {

        foreach($params as $key => $value) {

            $path = str_replace('{' . $key . '}', $value, $path);

        }

        return Str::startsWith($path, '/') ? ltrim($path, '/') : $path;

    }

}