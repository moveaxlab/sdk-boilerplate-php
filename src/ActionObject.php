<?php

namespace SDK\Boilerplate;


use Illuminate\Support\Arr;
use SDK\Boilerplate\Contracts\Schemable;
use SDK\Boilerplate\Traits\HasAttributes;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use SDK\Boilerplate\Validation\ValidationFactory;

abstract class ActionObject implements Schemable, Arrayable, Jsonable, \JsonSerializable, \ArrayAccess
{

    use HasAttributes;

    /**
     * Originally passed items
     *
     * @var array
     */
    protected $originalData;

    /**
     * ActionObject constructor
     * .
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        $this->originalData = $attributes;

        $this->attributes = $this->parseSubObjects($attributes);

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
     * Parse the object from an array of attributes
     *
     * @param array|string $data
     * @return ActionObject
     */
    public static function parse($data)
    {

        $data = is_string($data) ? json_decode($data, true) : $data;

        return new static($data);

    }

    /**
     * Define the sub-objects
     *
     * @return array
     */
    protected abstract function subObjects();

    /**
     * Parse the sub-objects if any
     *
     * @param array $attributes
     * @return array
     */
    protected function parseSubObjects(array $attributes)
    {

        $parsedAttributes = $attributes;
        $subObjects = $this->subObjects();

        if(!count($subObjects)) return $attributes;

        foreach ($subObjects as $key => $class)
        {

            if(Arr::has($attributes, $key) && is_array($attributes[$key])) {
                $subObject = Arr::isAssoc($attributes[$key]) ?
                    new $class($attributes[$key])
                    : array_map(function($elem) use($class) { return new $class($elem); }, $attributes[$key]);

                $parsedAttributes[$key] = collect($subObject);
            }

        }

        return $parsedAttributes;
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
            if(static::subObjects() && array_key_exists($attribute, static::subObjects())) {
                $transformed[$attribute] = $value->toArray();
            } else {
                $transformed[$attribute] = $value;
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