<?php

namespace SDK\Boilerplate\Tests;


use Illuminate\Validation\ValidationException;
use SDK\Boilerplate\Validation\RuleMapper;
use SDK\Boilerplate\Validation\Spec;
use SDK\Boilerplate\Validation\ValidationFactory;
use Symfony\Component\Yaml\Yaml;

class ValidationTest extends TestCase
{

    public function typesDataProvider()
    {

        $vectors = Yaml::parseFile(__DIR__.'/../vendor/chainside/validation-testvectors/vectors.yaml', Yaml::PARSE_OBJECT_FOR_MAP);
        $types = $vectors->types;
        $provider = [];


        foreach ($types as $type => $outcomes) {

            foreach ($outcomes->success as $value) {
                $provider[] = [
                    $type,
                    $value,
                    true
                ];
            }

            foreach ($outcomes->failure as $value) {

                $provider[] = [
                    $type,
                    $value,
                    false
                ];

            }

        }

        return $provider;

    }

    public function specsDataProvider()
    {

        $vectors = Yaml::parseFile(__DIR__.'/../vendor/chainside/validation-testvectors/vectors.yaml');
        $specs = $vectors["specs"];

        $provider = [];

        foreach ($specs as $spec)
        {

            foreach ($spec["success"] as $value) {
                $provider[] = [
                    $spec['spec'],
                    $value,
                    true,
                    null
                ];
            }

            foreach ($spec["failure"] as $failureData) {

                $provider[] = [
                    $spec['spec'],
                    $failureData['data'],
                    false,
                    $failureData['failing']
                ];

            }

        }

        return $provider;

    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testTypesValidation($type, $value, $success)
    {

        $ruleMapper = new RuleMapper();
        $rule = $ruleMapper->mapType($type);

        $rules = [
            "data" => $rule
        ];

        $validator = ValidationFactory::make($value, $rules);

        $passes = $validator->passes();
        $this->assertEquals($success, $passes);

    }

    /**
     * @dataProvider specsDataProvider
     */
    public function testSpecValidation($spec, $data, $success, $failingInfo)
    {

        $rules = Spec::parse($spec)->toValidationArray();

        $validator = ValidationFactory::make($data ?: [], $rules);

        if(!$success) {
            $this->expectException(ValidationException::class);
        } else {
            $this->expectNotToPerformAssertions();
        }

        try {
            $validator->validate();
        } catch (ValidationException $ex) {
            throw $ex;
        }

    }


}