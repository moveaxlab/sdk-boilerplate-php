<?php

namespace SDK\Boilerplate\Tests\Objects;


use SDK\Boilerplate\ActionObject;
use SDK\Boilerplate\Validation\Spec;

class Kitten extends ActionObject
{

    public static function schema()
    {
        return Spec::parse([
            "type" => "object",
            "rules" => [],
            "schema" => [
                "uuid" => [
                    "rules" => ["required"],
                    "type" => "uuid"
                ],
                "color" => [
                    "rules" => ["required", "in:red,blue,green,yellow,orange,brown"],
                    "type" => "string"
                ],
                "name" => [
                    "rules" => ["required", "maxlen:32"],
                    "type" => "string"
                ],
                "date_of_birth" => [
                    "rules" => ["required"],
                    "type" => "ISO_8601_date"
                ],
                "owners" => [
                    'type' => 'array',
                    'rules' => 'required',
                    'elements' => Owner::schema()->withRules(['required'])->toArray()
                ]
            ]
        ]);
    }

    protected function subObjects()
    {
        return [
            'owners' => Owner::class
        ];
    }

}