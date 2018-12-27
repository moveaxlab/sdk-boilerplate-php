<?php

namespace SDK\Boilerplate\Validation\Rules;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Support\Str;

class Iso8601Rule implements Rule
{

    public static function apply(\Illuminate\Validation\Factory $validation)
    {

        $validation->extend('iso_date', function($attribute, $value, $parameters, $validator) {

            // 2012-04-23T18:25:43.511Z
            // Regex from https://www.myintervals.com/blog/2009/05/20/iso-8601-date-validation-that-doesnt-suck/
            $regex = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';
            $matches = [];
            if(!preg_match($regex, $value, $matches)) return false;

            if(!isset($matches[1])) return false;
            $year = intval($matches[1]);

            if(!isset($matches[5])) return false;
            $month = intval($matches[5]);

            if(!isset($matches[7])) return false;
            $day = intval($matches[7]);

            $hour = intval(isset($matches[15]) ? $matches[15] : 0);
            $minute = intval(isset($matches[16]) ? Str::replaceFirst(':', '', $matches[16]) : 0);
            $second = intval(isset($matches[19]) ? Str::replaceFirst(':', '', $matches[19]) : 0);

            try {
                Carbon::createSafe($year, $month, $day, $hour, $minute, $second);
            } catch (InvalidDateException $ex) {
                return false;
            }

            return true;

        }, "The :attribute field must be a valid ISO 8601 date.");

    }

}