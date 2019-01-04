<?php

namespace SDK\Boilerplate\Callbacks;


use Illuminate\Support\Str;
use SDK\Boilerplate\Context;
use SDK\Boilerplate\Exceptions\CallbackVerificationException;
use Symfony\Component\HttpFoundation\Request;
use SDK\Boilerplate\Contracts\CallbackFactory;

abstract class CallbackHandler
{

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Request
     */
    protected $request;

    /**
     * CallbackHandler constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {

        $this->context = $context;
        $this->request = $this->captureRequest();

    }

    /**
     * Parse the received callback
     *
     * @return \SDK\Boilerplate\Contracts\Callback
     * @throws CallbackVerificationException
     */
    public function parse()
    {

        if(!$this->verify())
            throw new CallbackVerificationException();

        $factory = $this->callbackFactoryClass();

        if(!$factory instanceof CallbackFactory)
            throw new \InvalidArgumentException('Callback factory must be of class ' . CallbackFactory::class);

        $data = Str::contains($this->request->headers->get('CONTENT_TYPE'), ['/json', '+json']) ?
            json_decode($this->request->getContent(), true) :
            $this->request->getContent();

        return $factory::make($this->request->headers->all(), $data);

    }

    /**
     * Return the class of the
     *
     * @return string
     */
    abstract protected function callbackFactoryClass();

    /**
     * Verifies the received data
     *
     * @return bool
     */
    abstract protected function verify();

    /**
     * Capture the request from PHP Globals
     *
     * @return Request
     */
    protected function captureRequest()
    {

        return Request::createFromGlobals();

    }

}