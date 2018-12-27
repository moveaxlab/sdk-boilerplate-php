<?php

namespace SDK\Boilerplate;


use Illuminate\Support\Collection;
use SDK\Boilerplate\Contracts\Schemable;
use SDK\Boilerplate\Validation\ValidationFactory;

abstract class ActionObjectCollection extends Collection implements Schemable
{

    /**
     * Originally passed items
     *
     * @var array
     */
    protected $originalData;

    public function __construct($items = [])
    {

        $this->originalData = $items;
        $actionObjectClass = $this->elementsClass();

        $items = array_map(function($item) use($actionObjectClass){
           return $actionObjectClass::parse($item);
        }, $items);

        parent::__construct($items);

    }

    /**
     * Returns the original passed data
     *
     * @return array
     */
    public function getOriginalData()
    {
        return $this->originalData;
    }

    /**
     * Parse an array of items into a collection of ActionObjects
     *
     * @param array $items
     * @return ActionObjectCollection
     */
    public static function parse($items = []) {

        return new static($items);

    }

    /**
     * Specify the class of the elements
     *
     * @return string
     */
    protected abstract function elementsClass();

    /**
     * Make the object validator
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validator()
    {
        $schema = static::schema();
        return ValidationFactory::make($this->toArray(), $schema->toValidationArray());

    }

    /**
     * Validates the object. If the validation fails an exception is thrown
     *
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate()
    {

        $validator = $this->validator();

        $validator->validate();

        return true;
    }

    /**
     * Check if the object passes the validation without throwing an exception
     *
     * @return bool
     */
    public function passesValidation()
    {

        $validator = $this->validator();

        return $validator->passes();

    }

    /**
     * Check if the object does not pass the validation without throwing an exception
     *
     * @return bool
     */
    public function failsValidation()
    {

        $validator = $this->validator();

        return $validator->fails();

    }
}