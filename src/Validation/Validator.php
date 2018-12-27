<?php


namespace SDK\Boilerplate\Validation;


use Illuminate\Contracts\Translation\Translator;

class Validator extends \Illuminate\Validation\Validator
{

    protected $numericRules = ['Numeric', 'Integer', 'Float'];

    public function __construct(Translator $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
    {

        parent::__construct($translator, $data, $rules, $messages, $customAttributes);

    }

    protected function validateBoolean($attribute, $value)
    {
        return is_bool($value);
    }

    protected function validateInteger($attribute, $value)
    {
        return is_int($value);
    }

    protected function validateFloat($attribute, $value)
    {
        return is_int($value) || is_float($value);
    }

    protected function validateObject($attribute, $value)
    {
        return is_object($value) || is_array($value);
    }

    protected function validateUrl($attribute, $value)
    {

        $pattern = '~^
                    # http:// or https:// or ftp:// or ftps://
                    (?:http|ftp)s?://
                    # domain...
                    (?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+
                    (?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)
                    |
                    # ...or ipv4
                    \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}
                    |
                    # ...or ipv6
                    \[?[A-F0-9]*:[A-F0-9:]+\]?)
                    # optional port
                    (?::\d+)?
                    (?:/?|[/?]\S+)
                    $~ix';

        return preg_match($pattern, $value) > 0;

    }

}