<?php

namespace SDK\Boilerplate\Tests\Factories;

use SDK\Boilerplate\Tests\Actions\GetKitten;
use SDK\Boilerplate\Tests\Actions\GetKittensList;

class KittensFactory extends \SDK\Boilerplate\ActionFactory
{

    public static function actions()
    {
        return [

            'kittens.list' => GetKittensList::class,
            'kittens.show' => GetKitten::class

        ];
    }

}