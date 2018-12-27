<?php

namespace SDK\Boilerplate\Validation;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use SDK\Boilerplate\Validation\Rules\Base58Rule;
use SDK\Boilerplate\Validation\Rules\Base64EncodedFileRule;
use SDK\Boilerplate\Validation\Rules\Base64Rule;
use SDK\Boilerplate\Validation\Rules\EqualsRule;
use SDK\Boilerplate\Validation\Rules\FileFormatRule;
use SDK\Boilerplate\Validation\Rules\FileTypeRule;
use SDK\Boilerplate\Validation\Rules\HexRule;
use SDK\Boilerplate\Validation\Rules\Iso8601Rule;
use SDK\Boilerplate\Validation\Rules\MaxSizeRule;
use SDK\Boilerplate\Validation\Rules\MinSizeRule;
use SDK\Boilerplate\Validation\Rules\MustBeTrueRule;
use SDK\Boilerplate\Validation\Rules\SequenceRule;
use SDK\Boilerplate\Validation\Rules\UuidRule;

class ValidationFactory
{

    const LANG_DIR = __DIR__. '/../lang/';
    const DEFAULT_LOCALE = 'en';
    const FILENAME = 'validation.php';

    static $extends = [
        Base64EncodedFileRule::class,
        Iso8601Rule::class,
        Base64Rule::class,
        Base58Rule::class,
        SequenceRule::class,
        EqualsRule::class,
        HexRule::class,
        UuidRule::class,
        FileFormatRule::class,
        FileTypeRule::class,
        MinSizeRule::class,
        MaxSizeRule::class,
        MustBeTrueRule::class
    ];

    /**
     * Returns an instance of a validator
     *
     * @param mixed $data
     * @param array $rules
     *
     * @return \Illuminate\Validation\Validator
     */
    public static function make($data, array $rules)
    {

        $files = new Filesystem();
        $loader = new FileLoader($files, self::getLangPath());
        $translator = new Translator($loader, self::DEFAULT_LOCALE);
        $factory = new CustomIlluminateValidationFactory($translator);

        foreach (self::$extends as $extendRule) {
            $extendRule::apply($factory);
        }

        $data = [
            'data' => $data
        ];

        dump($data);
        dump($rules);

        return $factory->make($data, $rules);

    }

    /**
     * Get the validation language file path
     *
     * @return string
     */
    protected static function getLangPath()
    {
        return self::LANG_DIR . self::DEFAULT_LOCALE . '/' . self::FILENAME;
    }


}