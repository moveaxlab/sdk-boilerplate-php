<?php

namespace SDK\Boilerplate;


use SDK\Boilerplate\Contracts\Factory;
use SDK\Boilerplate\Http\GuzzleHttpClient;

class RequestFactory implements Factory
{

    /**
     * The sdk context
     *
     * @var Context
     */
    protected $context;

    public function __construct(Context $context)
    {

        $this->context = $context;

    }

    /**
     * @inheritdoc
     */
    public function make($what = null, ...$parameters)
    {

        $client = new GuzzleHttpClient();

    }

}