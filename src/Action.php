<?php

namespace SDK\Boilerplate;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\SimpleCache\CacheInterface;
use SDK\Boilerplate\Contracts\Hook;
use SDK\Boilerplate\Http\HttpClient;
use SDK\Boilerplate\Validation\Spec;
use SDK\Boilerplate\Utils\RouteCompiler;
use SDK\Boilerplate\Contracts\FailureHook;
use SDK\Boilerplate\Contracts\SuccessHook;
use SDK\Boilerplate\Contracts\PreSendHook;
use SDK\Boilerplate\Http\GuzzleHttpClient;
use SDK\Boilerplate\Exceptions\SdkException;
use Illuminate\Validation\ValidationException;
use SDK\Boilerplate\Exceptions\StatusException;
use SDK\Boilerplate\Validation\ValidationFactory;
use SDK\Boilerplate\Contracts\Request as IRequest;
use SDK\Boilerplate\Contracts\Response as IResponse;
use SDK\Boilerplate\Contracts\Action as ActionInterface;

abstract class Action implements ActionInterface
{


    /**
     * The application context
     *
     * @var Context
     */
    protected $context;

    /**
     * A cache instance
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * The HTTP Client
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * Query parameters array
     *
     * @var array
     */
    protected $queryParams = [];

    /**
     * Route parameters array
     *
     * @var array
     */
    protected $urlParams = [];

    /**
     * Request headers array
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Request Body Object
     *
     * @var ActionObject|ActionObjectCollection|null
     */
    protected $requestBody;

    /**
     * Response Body Object
     *
     * @var ActionObject|ActionObjectCollection
     */
    protected $responseBody;

    /**
     * Stack of the hooks to run before the request dispatching
     *
     * @var array
     */
    protected $preSendHooks = [];

    /**
     * Stack of the hooks to run if a request fails
     *
     * @var array
     */
    protected $failureHooks = [];

    /**
     * Stack of the hooks to run if the request succeeds
     *
     * @var array
     */
    protected $successHooks = [];

    /**
     * Mapping between status codes and exceptions
     *
     * @var array
     */
    protected $statusExceptions = [];

    /**
     * The original request
     *
     * @var IRequest
     */
    protected $originalRequest;

    /**
     * The exception thrown by the HTTP Client
     *
     * @var \Throwable|null
     */
    protected $clientException;

    /**
     * The request
     *
     * @var IRequest
     */
    protected $request;

    /**
     * The original response
     *
     * @var IResponse
     */
    protected $originalResponse;

    /**
     * The response
     *
     * @var IResponse
     */
    protected $response;

    /**
     * Action constructor.
     * @param Context $context
     * @param CacheInterface $cache
     */
    public function __construct(Context $context, CacheInterface $cache)
    {

        $this->context = $context;
        $this->cache = $cache;
        $this->buildClient();

    }

    protected function makeValidator(array $data, array $rules)
    {
        return ValidationFactory::make($data, $rules);
    }

    protected function buildClient()
    {
        $this->client = new GuzzleHttpClient([
            'timeout' => $this->context->getConfigValue('request_timeout', 20),
            'verify' => $this->context->getConfigValue('verify_ssl_certs', true)
        ]);
    }

    /**
     * Get the application context
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Get the cache instance
     *
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Get the base url
     *
     * @return string
     */
    public function getBaseUrl()
    {

        return $baseUrl = Str::endsWith($this->getContext()->hostname, '/')
            ? $this->getContext()->hostname
            : $this->getContext()->hostname . '/';

    }

    /**
     * Returns the request
     *
     * @return IRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response
     *
     * @return IResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the Request Body Object
     *
     * @return ActionObject|ActionObjectCollection|null
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * Set the request body object
     *
     * @param ActionObject|ActionObjectCollection $object
     * @throws ValidationException
     */
    public function setRequestBody($object)
    {

        $object->validate();
        $this->requestBody = $object;

    }

    /**
     * Check if the response is present
     *
     * @return bool
     */
    public function hasResponse()
    {
        return !is_null($this->response);
    }

    /**
     * Returns the response body object
     *
     * @return ActionObject|ActionObjectCollection
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * Builds the endpoint
     *
     * @return string
     * @throws ValidationException
     */
    protected function buildUrl()
    {

        $compiled = $this->buildRoute();
        $baseUrl = $this->getBaseUrl();


        return "$baseUrl$compiled";

    }

    /**
     * Returns the request object class
     *
     * @return string
     */
    protected abstract function requestClass();

    /**
     * Returns the response object class
     *
     * @return string
     */
    protected abstract function responseClass();


    /**
     * Builds the request
     *
     * @return Request
     * @throws ValidationException
     */
    protected function makeRequest()
    {

        $this->makeValidator($this->queryParams, static::queryParametersSchema()->toValidationArray());

        return new Request(
            static::verb(),
            $this->buildUrl(),
            $this->queryParams,
            $this->headers,
            $this->buildBody()
        );

    }

    /**
     * Builds the route parameters validation schema
     *
     * @return Spec
     */
    public static function routeParametersSchema()
    {

        return Spec::parse([
            'rules' => ['nullable'],
            'schema' => [],
            'type' => "object"
        ]);

    }

    /**
     * Returns the route parameters array
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->urlParams;
    }

    /**
     * Add a route parameter
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addRouteParameter($key, $value)
    {
        Arr::set($this->urlParams, $key, $value);
        return $this;
    }

    /**
     * Remove a route parameter
     *
     * @param string $key
     * @return $this
     */
    public function removeRouteParameter($key)
    {
        Arr::forget($this->urlParams, $key);
        return $this;
    }

    /**
     * Set the route parameters array
     *
     * @param array $routeParameters
     * @return $this
     */
    public function setRouteParameters(array $routeParameters)
    {
        $this->queryParams = $routeParameters;
        return $this;
    }

    /**
     * Builds the route
     *
     * @return mixed|string
     * @throws ValidationException
     */
    protected function buildRoute()
    {

        $validator = $this->makeValidator($this->urlParams, static::routeParametersSchema()->toValidationArray());
        $validator->validate();
        return RouteCompiler::compile(static::route(), $this->urlParams);

    }

    /**
     * Builds the query parameters validation schema
     *
     * @return Spec
     */
    public static function queryParametersSchema()
    {

        return Spec::parse([
            'rules' => ['nullable'],
            'schema' => [],
            'type' => "object"
        ]);

    }

    /**
     * Returns the query parameters array
     *
     * @return array
     */
    public function getQueryParameters()
    {
        return $this->queryParams;
    }

    /**
     * Add a query string parameter
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addQueryParameter($key, $value)
    {
        Arr::set($this->queryParams, $key, $value);
        return $this;
    }

    /**
     * Remove a query string parameter
     *
     * @param string $key
     * @return $this
     */
    public function removeQueryParameter($key)
    {
        Arr::forget($this->queryParams, $key);
        return $this;
    }

    /**
     * Set the query parameters array
     *
     * @param array $queryParams
     * @return $this
     */
    public function setQueryParameters(array $queryParams)
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * Builds the request body
     *
     * @return array
     * @throws ValidationException
     */
    protected function buildBody()
    {

        if(is_null($this->requestBody)) {
            return null;
        }

        $this->requestBody->validate();
        return $this->requestBody->toArray();

    }

    /**
     * Add a pre-send hook
     *
     * @param string $hookClass
     * @throws SdkException
     */
    public function addPreSendHook($hookClass)
    {
        if(!is_subclass_of(PreSendHook::class, $hookClass))
            throw new SdkException(
                "Pre-send hooks must implement interface " . PreSendHook::class
            );

        $this->preSendHooks[] = $hookClass;
    }

    /**
     * Add a failure hook
     *
     * @param string $hookClass
     * @throws SdkException
     */
    public function addFailureHook($hookClass)
    {
        if(!is_subclass_of(FailureHook::class, $hookClass))
            throw new SdkException(
                "Failure hooks must implement interface " . FailureHook::class
            );

        $this->failureHooks[] = $hookClass;
    }

    /**
     * Add a success hook
     *
     * @param string $hookClass
     * @throws SdkException
     */
    public function addSuccessHook($hookClass)
    {

        if(!is_subclass_of(SuccessHook::class, $hookClass))
            throw new SdkException(
                "Success hooks must implement interface " . SuccessHook::class
            );

        $this->successHooks[] = $hookClass;
    }


    /**
     * Add a status code exception
     *
     * @param mixed $statusCode
     * @param string $exceptionClass
     * @throws SdkException
     */
    public function addStatusException($statusCode, $exceptionClass)
    {

        if(!is_string($exceptionClass))
            throw new SdkException("Exception class in status exceptions must be a string");

        if(!is_subclass_of(StatusException::class, $exceptionClass))
            throw new SdkException(
                "Status exceptions must implement interface " . StatusException::class
            );

        $this->statusExceptions[(string)$statusCode] = $exceptionClass;

    }

    /**
     * Run some hooks
     *
     * @param array $hooks
     * @param RunState $state
     */
    public function runHooks(array $hooks, RunState $state)
    {

        foreach ($hooks as $hookClass) {

            if(is_subclass_of($hookClass, PreSendHook::class)) {
                $hookInstance = new $hookClass($this, $this->request);
            } else if(is_subclass_of($hookClass, FailureHook::class)) {
                $hookInstance = new $hookClass($this, $this->response, $this->clientException);
            } else {
                $hookInstance = new $hookClass($this, $this->response);
            }

            /**
             * @var Hook $hookInstance
             */
            $hookInstance->run($state);
        }

    }

    /**
     * Raise the proper status exception
     *
     * @throws StatusException
     */
    protected function raiseStatusException()
    {

        $statusCode = $this->response->statusCode();
        if(array_key_exists($statusCode, $this->statusExceptions)) {

            $exception = new $this->statusExceptions[$statusCode]($this);

        } else {

            $exception = new StatusException($this, static::class . " failed with status {$statusCode}");

        }

        /**
         *
         * @var StatusException
         */
        $exception->setRequest($this->request);
        $exception->setResponse($this->response);

        throw $exception;

    }

    /**
     * Validates the request body
     *
     * @throws ValidationException
     */
    protected function validateRequest()
    {

        $requestClass = $this->requestClass();
        if(empty($requestClass)) return;
        $schema = $requestClass::schema();
        $validator = $this->makeValidator($this->request->body(), $schema);
        $validator->validate();

    }

    /**
     * Validates the response
     *
     * @throws ValidationException
     */
    protected function validateResponse()
    {

        $responseClass = $this->responseClass();
        $schema = $responseClass::schema();
        $validator = $this->makeValidator($this->response->body(), $schema);
        $validator->validate();

    }

    /**
     * Runs the action and returns the response
     *
     * @return ActionObject|ActionObjectCollection
     * @throws \Exception|StatusException
     */
    public function run()
    {

        $runState = new RunState();
        $this->originalRequest = $this->request = $this->makeRequest();
        $this->runHooks($this->preSendHooks, $runState);

        try {

            $this->validateRequest();
            $this->originalResponse = $this->response = $this->client->send($this->request);

        } catch (\Exception $e) {

            $this->clientException = $e;
            if($this->client->hasResponse()) $this->originalResponse = $this->response = $this->client->getResponse();
            $this->runHooks($this->failureHooks, $runState);

            if(is_null($this->response)) throw $e;

        }

        if($this->response->statusCode() >= 300) {
            $this->raiseStatusException();
        }


        $this->runHooks($this->successHooks, $runState);
        $responseBodyClass = static::responseClass();
        $this->responseBody =  $responseBodyClass::parse($this->response->body());
        $this->responseBody->validate();

        return $this->responseBody;

    }



}