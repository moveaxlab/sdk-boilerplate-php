<?php

namespace SDK\Boilerplate\Validation;


use Illuminate\Support\Str;
use SDK\Boilerplate\Exceptions\SdkException;

class RuleMapper
{

    protected $prefix;

    public function __construct($prefix = '')
    {

        $this->prefix = $prefix;

    }

    public function setPrefix($prefix)
    {
        return $this->prefix = $prefix;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public static function mapRules($type, array $rules)
    {

        $mapper = new self;

        $mappedRules = array_flatten([
            $mapper->mapType($type)
        ]);

        foreach ($rules as $rule) {
            $mappedRules = array_merge($mappedRules, $mapper->mapRule($rule));
        }

        return $mappedRules;
    }

    public function mapRule($rule)
    {

        $ruleParts = explode(':',$rule, 2);
        $ruleName = $ruleParts[0];
        if($ruleName !== 'regex') {
            $parameters = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
        } else {
            $parameters = [ $ruleParts[1] ];
        }

        $method = Str::camel("map_{$ruleName}_rule");
        if(!method_exists($this, $method)) throw new SdkException("Validation rule '$rule' not supported");

        return $this->$method(...$parameters);
    }

    public function mapType($type)
    {
        $method = Str::studly("map_{$type}_type");
        if(!method_exists($this, $method)) throw new SdkException("Type '$type' validation not supported");

        return $this->$method();
    }

    protected function mapArrayType()
    {

        return ["array"];

    }

    protected function mapBase58Type()
    {
        return ['base58'];
    }

    protected function mapBase64Type()
    {
        return ['base64'];
    }

    protected function mapBase64EncodedFileType()
    {
        return ['base64_encoded_file'];
    }

    protected function mapBooleanType()
    {
        return ['boolean'];
    }

    protected function mapEmailType()
    {
        return ['email'];
    }

    protected function mapFloatType()
    {
        return ['float'];
    }

    protected function mapIntegerType()
    {
        return ['integer'];
    }

    protected function mapISO8601DateType()
    {
        return ['iso_date'];
    }

    protected function mapObjectType()
    {
        return ['object'];
    }

    protected function mapSequenceType()
    {
        return ['sequence'];
    }

    protected function mapUrlType()
    {
        return ['url'];
    }

    protected function mapStringType()
    {
        return ['string'];
    }

    protected function mapUuidType()
    {
        return ['uuid'];
    }

    protected function mapAlphaRule()
    {
        return ['alpha'];
    }

    protected function mapAlphadashRule()
    {
        return ['alpha_dash'];
    }

    protected function mapAlphanumRule()
    {
        return ['alpha_num'];
    }

    protected function mapBetweenRule($min, $max)
    {
        return ["between:$min,$max"];
    }

    protected function mapBetweenlenRule($min, $max)
    {
        return ["between:$min,$max"];
    }

    protected function mapDecimalRule()
    {
        return ['numeric'];
    }

    protected function mapEqualsRule($value)
    {
        return ["equals:$value"];
    }

    protected function mapEqualsToRule($field, $otherField)
    {
        return ["same:{$this->prefix}$otherField", "same:{$this->prefix}$field"];
    }

    protected function mapFileFormatRule(...$types)
    {
        return ["file_format:" . join(',', $types)];
    }

    protected function mapFileTypeRule(...$formats)
    {
        return ["file_type:" . join(',', $formats)];
    }

    protected function mapHexRule()
    {
        return ["hex"];
    }

    protected function mapInRule(...$values)
    {
        return ['in:' . join(',', $values)];
    }

    protected function mapLenRule($length)
    {
        return ["size:$length"];
    }

    protected function mapMaxRule($maxVal)
    {
        return ["max:$maxVal"];
    }

    protected function mapMaxlenRule($len)
    {
        return ['max:' . $len];
    }

    protected function mapMaxSizeRule($size)
    {
        return ["max_size:$size"];
    }

    protected function mapMinRule($min)
    {
        return ["min:$min"];
    }

    protected function mapMinlenRule($min)
    {
        return ["min:$min"];
    }

    protected function mapMinSizeRule($size)
    {
        return ["min_size:$size"];
    }

    protected function mapMustBeTrueRule()
    {
        return ["must_be_true"];
    }

    protected function mapNullableRule()
    {
        return ["nullable"];
    }

    protected function mapOperationalFieldTmpRule($field, $value)
    {

        return ["required_if:{$this->prefix}$field,$value"];

    }

    protected function mapPresentRule()
    {
        return ["present"];
    }

    protected function mapRegexRule($pattern)
    {
        return ["regex:/$pattern/"];
    }

    protected function mapRequiredRule()
    {
        return ["required"];
    }

}