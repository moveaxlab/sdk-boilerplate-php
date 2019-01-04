<?php

namespace SDK\Boilerplate\Callbacks;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use SDK\Boilerplate\ActionObject;
use SDK\Boilerplate\Contracts\Schemable;
use SDK\Boilerplate\Traits\HasAttributes;
use SDK\Boilerplate\Contracts\Callback as CallbackInterface;
use SDK\Boilerplate\Validation\ValidationFactory;

abstract class Callback implements CallbackInterface, Schemable, Arrayable, Jsonable, \JsonSerializable, \ArrayAccess
{

    use HasAttributes;

    /**
     * The array of the original data received
     *
     * @var array
     */
    protected $originalData = [];

    /**
     * Callback constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {

        $this->originalData = $attributes;
        $this->attributes = $attributes;

        $this->parseObject();

    }

    /**
     * Returns the parsed ActionObject
     *
     * @return ActionObject
     */
    public function getObject()
    {
        return Arr::get($this->attributes, static::objectKey(), null);
    }

    /**
     * Parses the object
     *
     * @return void
     */
    protected function parseObject()
    {

        $objectClass = static::objectClass();
        $objectData = Arr::get($this->attributes, static::objectKey());


        Arr::set($this->attributes, static::objectKey(), $objectClass::parse($objectData));
    }

    /**
     * Return the array representation of the object
     *
     * @return array
     */
    public function toArray()
    {

        $array = $this->attributesToArray();
        $transformed = $array;

        foreach ($array as $attribute => $value)
        {
            if($value instanceof ActionObject) {
                $transformed[$attribute] = $value->toArray();
            }
        }

        return $transformed;
    }

    /**
     * Return the json representation of the object
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Serializes the object to a JSON serializable object
     *
     * @return mixed|string
     */
    public function jsonSerialize()
    {
        return $this->toArray();
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