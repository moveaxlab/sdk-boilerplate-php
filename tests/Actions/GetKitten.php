<?php

namespace SDK\Boilerplate\Tests\Actions;


use SDK\Boilerplate\Action;
use SDK\Boilerplate\Validation\Spec;
use SDK\Boilerplate\Tests\Objects\Kitten;
use SDK\Boilerplate\Tests\Hooks\AddTestHeaders;
use SDK\Boilerplate\Tests\Hooks\DumpKittenData;
use SDK\Boilerplate\Tests\Hooks\FallbackKittenHook;
use SDK\Boilerplate\Tests\Hooks\ChangeKittenColorHook;
use SDK\Boilerplate\Tests\Exceptions\KittenNotFoundException;

class GetKitten extends Action
{

    protected $preSendHooks = [
        AddTestHeaders::class
    ];

    protected $successHooks = [
        ChangeKittenColorHook::class,
        DumpKittenData::class
    ];

    protected $failureHooks = [
        FallbackKittenHook::class
    ];

    protected $statusExceptions = [
        '404' => KittenNotFoundException::class
    ];

    public static function verb()
    {
        return "GET";
    }

    public static function route()
    {
        return "/kittens/{uuid}";
    }

    public static function routeParametersSchema()
    {
        return Spec::parse([
            'type' => 'object',
            'rules' => ['required'],
            'schema' => [
                'uuid' => [
                    'type' => 'uuid',
                    'rules' => 'required'
                ]
            ]
        ]);
    }

    protected function requestClass()
    {
        return "";
    }

    protected function responseClass()
    {
        return Kitten::class;
    }

}