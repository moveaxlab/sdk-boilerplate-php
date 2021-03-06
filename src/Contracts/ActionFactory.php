<?php


namespace SDK\Boilerplate\Contracts;


interface ActionFactory extends Factory
{

    /**
     * Defines the name => action mapping
     *
     * @return array
     */
    static function actions();

    /**
     * @inheritdoc
     */
    public function make($what = null, ...$parameters);

}