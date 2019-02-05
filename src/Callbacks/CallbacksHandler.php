<?php

namespace SDK\Boilerplate\Callbacks;


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
     * Headers list
     *
     * @var array
     */
    protected $headers;

    /**
     * Request body
     *
     * @var string
     */
    protected $body;

    /**
     * CallbacksHandler constructor.
     *
     * @param array $headers
     * @param string $body
     * @param Context $context
     */
    public function __construct(Context $context, array $headers, $body)
    {

        $this->context = $context;
        $this->headers = $headers;
        $this->body = $body;

    }

    /**
     * Returns an instance of the CallbackHandler from the PHP Globals
     *
     * @param Context $context
     * @return CallbacksHandler
     */
    public static function createFromGlobals(Context $context)
    {

        $request = Request::createFromGlobals();
        $body = $request->getContent();
        $headers = $request->headers->all();

        return new static($context, $headers, $body);

    }

    /**
     * Parse the callback from global PHP variables such as $_SERVER, $_HTTP, ...
     *
     * @param Context $context
     *
     * @return SdkObject|SdkObjectCollection
     * @throws
     */
    public static function parseFromGlobals(Context $context)
    {

        $request = Request::createFromGlobals();
        $body = $request->getContent();
        $headers = $request->headers->all();

        $handler = new static($context, $headers, $body);

        return $handler->parse();
    }

    /**
     * Parse the received callback
     *
     * @return SdkObject|SdkObjectCollection
     * @throws CallbackVerificationException|CallbackParsingException
     */
    public function parse()
    {

        if(!$this->verify())
            throw new CallbackVerificationException();

        $key = $this->getCallbackNamespace();
        if(!array_key_exists($key, $this->callbacks))
            throw new CallbackParsingException("Could not find Callback class for key '$key'");

        $callbackClass = $this->callbacks[$key];

        if(
            !is_subclass_of($callbackClass, SdkObject::class, true) &&
            !is_subclass_of($callbackClass, SdkObjectCollection::class, true)
        )

        $body = $this->parseBody();

        return $callbackClass::parse($body);

    }

    /**
     * Parses the body or returns the raw body
     *
     * @return mixed|string
     */
    protected function parseBody()
    {
        return Str::contains($this->headers['Content-Type'], ['/json', '+json']) ?
            json_decode($this->body, true) :
            $this->body;
    }

    /**
     * Returns the parsed body
     *
     * @return mixed|string
     */
    public function getCallbackBody()
    {
        return $this->parseBody();
    }

    /**
     * Defines how to retrieve the $callbacks mapping key from the received request
     *
     * @return string
     */
    abstract protected function getCallbackNamespace();

    /**
     * Verifies the received data
     *
     * @return bool
     */
    abstract protected function verify();

}