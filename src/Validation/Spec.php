<?php

namespace SDK\Boilerplate\Validation;


use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class Spec implements Arrayable, Jsonable, \JsonSerializable
{

    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';

    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules;

    /**
     * Object's schema
     *
     * @var Collection
     */
    protected $childSchemas;

    /**
     * Element type
     *
     * @var string
     */
    protected $type;

    /**
     * Object name
     *
     * @var string
     */
    protected $name;

    /**
     * Schema constructor.
     *
     * @param array $rules
     * @param array $schema
     * @param string $type
     * @param string $name
     */
    public function __construct($type, array $rules, $name = null, $schema = [])
    {

        $this->rules = $rules;
        $this->type = $type;
        $this->name = $name;
        $this->childSchemas = collect([]);


        if($this->type === self::TYPE_ARRAY && $schema) {
            $this->parseSubSchema(null, $schema);
        } else {

            foreach ($schema as $name => $props) {
                $this->parseSubSchema($name, $props);
            }

        }

    }

    /**
     * Return an instance of a parsed schema
     *
     * @param array $props
     * @return Spec
     */
    public static function parse(array $props)
    {

        $type = $props['type'];
        $schemaKey = $type === self::TYPE_ARRAY ? 'elements' : 'schema';

        return new self(
            $props['type'],
            self::rulesToArray($props['rules']),
            null,
            Arr::get($props, $schemaKey, [])
        );

    }

    /**
     * Add rules to the parent schema
     *
     * @param array $additionalRules
     * @return $this
     */
    public function withRules(array $additionalRules = [])
    {

        $this->rules = array_unique(array_merge($this->rules, $additionalRules));

        return $this;
    }

    /**
     * Parses the sub-schema
     *
     * @param string $name
     * @param array $props
     */
    protected function parseSubSchema($name, array $props)
    {

        if($props['type'] === self::TYPE_ARRAY) {
            $schema = $props['elements'];
        } else if($props['type'] === self::TYPE_OBJECT) {
            $schema = $props['schema'];
        } else {
            $schema = [];
        }


        $this->childSchemas->push(new self($props['type'], $this->rulesToArray($props['rules']), $name, $schema));

    }

    /**
     * Return the child schemas collection
     *
     * @return Collection
     */
    public function children()
    {
        return $this->childSchemas;
    }

    /**
     * Returns the spec type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the spec rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Return the parameter name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Always cast rules as array
     *
     * @param string|array $rules
     * @return array
     */
    protected static function rulesToArray($rules)
    {
        return is_string($rules) ? explode('|', $rules) : $rules;
    }

    /**
     * Converts the schema into a validation array
     *
     * @param string $prefix
     * @param bool $packed
     * @param integer $depth
     *
     * @return array
     * @throws
     */
    public function toValidationArray($prefix = '', $packed = false, $depth = 0)
    {

        $prefix = empty($prefix) ? 'data' : "$prefix";

        $rules = $this->buildRules($prefix , $depth);

        if($this->type === self::TYPE_ARRAY) $prefix .= empty($prefix) ? '*' : '.*';

        if($this->children()->isNotEmpty()) {

            foreach ($this->children() as $child) {

                if($this->type === self::TYPE_ARRAY) {
                    $childPrefix = "{$prefix}{$child->getName()}";
                } else {
                    $childPrefix = "{$prefix}.{$child->getName()}";
                }

                $childRules = $child->toValidationArray($childPrefix, $packed, $depth+1);
                if(in_array('nullable', $this->rules)) {
                    if(in_array('required', $childRules[$childPrefix])) {
                        $childRules[$childPrefix] = array_diff($childRules[$childPrefix], ['required']);
                        $childRules[$childPrefix] = 'required_with:' . $prefix;
                    }
                }
                $rules = array_merge_recursive($rules, $childRules);

            }

        }

        if($depth == 0 && $packed) {
            $rules = array_map(function($r) { return join('|', $r); }, $rules);
        }

        return $rules;

    }

    /**
     * Evaluates the global rules to apply
     *
     * @param string $prefix
     * @param integer $depth
     *
     * @return array
     * @throws
     */
    protected function buildRules($prefix, $depth)
    {

        /*$mappableRules = array_filter($this->rules, function($rule) use($depth){
            return $depth > 0 || Str::startsWith($rule, 'equals_to');
        });*/

        $mappableRules = $this->rules;

        if(in_array('nullable', $mappableRules) && in_array('required', $mappableRules)) {
            $mappableRules = array_diff($mappableRules, ['required']);
            $mappableRules[] = 'present';
        }

        $ruleMapper = new RuleMapper($prefix);
        $rules = [];
        $subFieldRules = [];

        foreach($mappableRules as $rule)
        {
            if(Str::startsWith($rule, 'equals_to')) {
                $fields = explode(',', str_replace_first('equals_to:', '', $rule));
                $fieldPrefix = empty($prefix) ? '' : $prefix . '.';
                $tmpPrefix = $ruleMapper->getPrefix();
                $ruleMapper->setPrefix($fieldPrefix);
                $mappedRule = $ruleMapper->mapRule($rule);
                $ruleMapper->setPrefix($tmpPrefix);
                $subFieldRules[$fieldPrefix.$fields[0]][] = $mappedRule[0];
                $subFieldRules[$fieldPrefix.$fields[1]][] = $mappedRule[1];
            } else {

                $rules = array_merge($rules, $ruleMapper->mapRule($rule));

            }
        }

        /*if($depth > 0) {
        }*/
        $rules = array_merge($rules, $ruleMapper->mapType($this->type));

        if(count($rules)) {
            return array_merge([$prefix => $rules], $subFieldRules);
        }

        return $subFieldRules;

    }

    /**
     * Convert the spec to array
     *
     * @return array
     */
    public function toArray()
    {

        $arr = [
            'type' => $this->type,
            'rules' => $this->rules,
        ];

        if($this->children()->isNotEmpty()) {

            $key = 'schema';
            if($this->type === self::TYPE_ARRAY) {
                $arr['elements'] = $this->children()->first()->toArray();
                return $arr;
            }

            $arr[$key] = [];

            foreach ($this->children() as $child)
            {
                $arr[$key][$child->name] = $child->toArray();
            }


        }

        return $arr;

    }

    /**
     * Transform the spec into a json encoded string
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Serializes the spec to a json serializable object
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }


}