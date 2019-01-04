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

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return ! is_null($this->getAttribute($key));
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

}