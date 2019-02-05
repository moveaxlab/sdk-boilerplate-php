<?php

namespace SDK\Boilerplate;


use ElevenLab\Validation\Spec;
use ElevenLab\Validation\ValidationFactory;
use Illuminate\Support\Collection;
use SDK\Boilerplate\Contracts\Schemable;

abstract class SdkObjectCollection extends Collection implements Schemable
{

    /**
     * Class of the sub elements
     *
     * @var string
     */
    protected $elementsClass;

    /**
     * Originally passed items
     *
     * @var array
     */
    protected $originalData;

    /**
     * SdkObjectCollection constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {

        $this->originalData = $items;
        $elementClass = $this->elementsClass;

        if(!Spec::isPrimitiveType($elementClass))
        {
            $items = $this->parseSdkObjects($items);
        }

        parent::__construct($items);

    }

    /**
     * Parses items as SdkObject instances
     *
     * @param $items
     * @return array
     */
    protected function parseSdkObjects(array $items)
    {

        $class = $this->elementsClass;
        return array_map(function($item) use($class){
            return $class::parse($item);
        }, $items);

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
     * @return SdkObjectCollection
     */
    public static function parse($items = []) {

        return new static($items);

    }

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