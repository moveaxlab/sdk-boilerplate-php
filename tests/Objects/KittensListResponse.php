<?php

namespace SDK\Boilerplate\Tests\Objects;

use SDK\Boilerplate\ActionObjectCollection;
use SDK\Boilerplate\Validation\Spec;

class KittensListResponse extends ActionObjectCollection
{

    public static function schema()
    {
        return Spec::parse([
            "rules" => [],
            "type" => "array",
            "elements" => Kitten::schema()->withRules(['required'])->toArray()
        ]);
    }

    protected function elementsClass()
    {
        return Kitten::class;
    }

}