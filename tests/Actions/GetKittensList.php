<?php

namespace SDK\Boilerplate\Tests\Actions;


use SDK\Boilerplate\Action;
use SDK\Boilerplate\Tests\Objects\KittensListResponse;
use SDK\Boilerplate\Validation\Spec;

class GetKittensList extends Action
{

    public static function verb()
    {
        return "GET";
    }

    public static function route()
    {
        return "/kittens";
    }

    protected function requestClass()
    {
        return "";
    }

    protected function responseClass()
    {
        return KittensListResponse::class;
    }

}