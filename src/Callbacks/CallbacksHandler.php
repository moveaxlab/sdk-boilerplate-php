<?php

namespace SDK\Boilerplate\Callbacks;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SDK\Boilerplate\Context;
use SDK\Boilerplate\SdkObject;
use SDK\Boilerplate\SdkObjectCollection;
use Symfony\Component\HttpFoundation\Request;
use SDK\Boilerplate\Exceptions\CallbackParsingException;
use SDK\Boilerplate\Exceptions\CallbackVerificationException;

abstract class CallbacksHandler
{

    /**
     * Callbacks mapping
     */
    protected $callbacks = [];

    /**
     * @var Context
     */
    protected $context;

    /**
     * CallbacksHandler constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {

        $this->context = $context;

    }

    /**
     * Parse the callback from global PHP variables such as $_SERVER, $_HTTP, ...
     *
     *
     * @return SdkObject|SdkObjectCollection
     * @throws
     */
    public function parseFromGlobals()
    {

        $request = Request::createFromGlobals();
        $body = $request->getContent();
        $headers = [];

        foreach ($request->headers->all() as $key => $value)
        {
            $headers[$key] = is_array($value) ? reset($value) : $value;
        }

        return $this->parse($headers, $body);
    }

    /**
     * Parse the received callback
     *
     * @param array $headers
     * @param string $body
     *
     * @return SdkObject|SdkObjectCollection
     * @throws CallbackVerificationException|CallbackParsingException
     */
    public function parse(array $headers, $body)
    {

        $headers = array_change_key_case($headers, CASE_LOWER);

        if(!$this->verify($headers, $body))
            throw new CallbackVerificationException();

        $key = $this->getCallbackNamespace($headers, $body);
        if(!array_key_exists($key, $this->callbacks))
            throw new CallbackParsingException("Could not find Callback class for key '$key'");

        $callbackClass = $this->callbacks[$key];

        if(
            !is_subclass_of($callbackClass, SdkObject::class, true) &&
            !is_subclass_of($callbackClass, SdkObjectCollection::class, true)
        )

        $body = $this->parseBody($headers, $body);

        return $callbackClass::parse($body);

    }

    /**
     * Parses the body or returns the raw body
     *
     * @param array $headers
     * @param string $body
     *
     * @return mixed|string
     */
    protected function parseBody(array $headers, $body)
    {
        return Arr::has($headers, 'content-type') && Str::contains($headers['content-type'], ['/json', '+json']) ?
            json_decode($body, true) :
            $body;
    }

    /**
     * Defines how to retrieve the $callbacks mapping key from the received request
     *
     * @param array $headers
     * @param string $body
     *
     * @return string
     */
    abstract protected function getCallbackNamespace(array $headers, $body);

    /**
     * Verifies the received data
     *
     * @param array $headers
     * @param string $body
     *
     * @return bool
     */
    abstract protected function verify(array $headers, $body);

}