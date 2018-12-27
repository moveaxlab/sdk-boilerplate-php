<?php

namespace SDK\Boilerplate\Tests\Objects;


use SDK\Boilerplate\ActionObject;
use SDK\Boilerplate\Validation\Spec;

class Owner extends ActionObject
{

    public static function schema()
    {
        return Spec::parse([
            'type' => 'object',
            'rules' => [],
            'schema' => [
                "uuid" => [
                    'rules' => ['required'],
                    'type' => 'uuid'
                ],
                "first_name" => [
                    'rules' => ['required', 'maxlen:32'],
                    'type' => 'string'
                ],
                "last_name" => [
                    'rules' => ['required', 'maxlen:32'],
                    'type' => 'string'
                ],
                "address" => [
                    'rules' => ['required', 'maxlen:255'],
                    'type' => 'string'
                ]
            ]
        ]);
    }

    protected function subObjects()
    {
        return [];
    }

}