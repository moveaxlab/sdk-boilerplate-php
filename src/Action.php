<?php

namespace SDK\Boilerplate;

use ElevenLab\Validation\Spec;
use ElevenLab\Validation\ValidationFactory;
use Illuminate\Support\Arr;
use SDK\Boilerplate\Contracts\Hook;
use SDK\Boilerplate\Utils\RouteCompiler;
use SDK\Boilerplate\Hooks\FailureHook;
use SDK\Boilerplate\Hooks\SuccessHook;
use SDK\Boilerplate\Hooks\PreSendHook;
use SDK\Boilerplate\Exceptions\SdkException;
use Illuminate\Validation\ValidationException;
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
     * The endpoint string
     *
     * @var string
     */
    protected $route;

    /**
     * The HTTP verb
     *
     * @var string
     */
    protected $verb;

    /**
     * The query parameters schema
     *
     * @var array
     */
    protected $queryParametersSchema = [];

    /**
     * Query parameters array
     *
     * @var array
     */
    protected $queryParameters = [];

    /**
     * The route parameters schema
     *
     * @var array
     */
    protected $routeParametersSchema = [];

    /**
     * Route parameters array
     *
     * @var array
     */
    protected $routeParameters = [];

    /**
     * Request headers array
     *
     * @var array
     */
    protected $defaultHeaders = [];

    /**
     * Request Body Object
     *
     * @var SdkObject|SdkObjectCollection|null
     */
    protected $requestBody;

    /**
     * Request Body Class
     *
     * @var string
     */
    protected $requestBodyClass;

    /**
     * Response Body Class
     *
     * @var string
     */
    protected $responseBodyClass;

    /**
     * Response Body Object
     *
     * @var SdkObject|SdkObjectCollection
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
    protected $errors = [];

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
     */
    public function __construct(Context $context)
    {

        $this->context = $context;

    }

    /**
     * Validates data
     *
     * @param array $data
     * @param array $rules
     *
     * @throws ValidationException
     */
    protected function validate(array $data, array $rules)
    {
        $validator = ValidationFactory::make($data, $rules);
        $validator->validate();
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
     * @return SdkObject|SdkObjectCollection|null
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * Set the request body object
     *
     * @param SdkObject|SdkObjectCollection $object
     */
    public function setRequestBody($object)
    {

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
     * @return SdkObject|SdkObjectCollection
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }


    /**
     * Builds the request
     *
     * @return Request
     * @throws ValidationException
     */
    protected function buildRequest()
    {

        if($this->queryParametersSchema) {
            $qpSchema = Spec::parse($this->queryParametersSchema);
            $this->validate($this->queryParameters, $qpSchema->toValidationArray());
        }

        return new Request(
            $this->verb,
            $this->buildRoute(),
            $this->queryParameters,
            $this->defaultHeaders,
            $this->buildBody()
        );

    }

    /**
     * Returns the route parameters array
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
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
        Arr::set($this->routeParameters, $key, $value);
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
        Arr::forget($this->routeParameters, $key);
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

        if($this->routeParametersSchema) {
            $rpSchema = Spec::parse($this->routeParametersSchema);
            $this->validate($this->routeParameters, $rpSchema->toValidationArray());
        }

        return RouteCompiler::compile($this->route, $this->routeParameters);

    }

    /**
     * Returns the query parameters array
     *
     * @return array
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
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
        Arr::set($this->queryParameters, $key, $value);
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
        Arr::forget($this->queryParameters, $key);
        return $this;
    }

    /**
     * Builds the request body
     *
     * @return array
     */
    protected function buildBody()
    {

        if(is_null($this->requestBody)) {
            return null;
        }

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
                "Pre-send hooks must be a subclass of " . PreSendHook::class
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
                "Failure hooks must be a subclass of " . FailureHook::class
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
                "Success hooks must be a subclass of " . SuccessHook::class
            );

        $this->successHooks[] = $hookClass;
    }

    /**
     * Run some hooks
     *
     * @param array $hooks
     */
    public function runHooks(array $hooks)
    {

        foreach ($hooks as $hookClass) {

            if(is_subclass_of( PreSendHook::class, $hookClass)) {
                $hookInstance = new $hookClass($this->context, $this->request);
            } else if(is_subclass_of( FailureHook::class, $hookClass)) {
                $hookInstance = new $hookClass($this->context, $this->request, $this->response, $this->clientException);
            } else {
                $hookInstance = new $hookClass($this->context, $this->request, $this->response);
            }

            /**
             * @var Hook $hookInstance
             */
            $hookInstance->run();
        }

    }

    /**
     *
     * @param IResponse $response
     *
     * @return string
     */
    protected function getExceptionKey(IResponse $response)
    {

        return (string)($response->statusCode());

    }

    /**
     * Returns the proper status exception
     *
     * @param string $key
     *
     * @return SdkException
     */
    protected function getException($key)
    {

        if(array_key_exists($key, $this->errors)) {

            $exception = new $this->errors[$key]($this);

        } else {

            $exception = new SdkException(static::class . " failed with status {$key}", $key);

        }

        return $exception;

    }

    /**
     * Validates the request body
     *
     * @throws ValidationException
     */
    protected function validateRequest()
    {

        $requestClass = $this->requestBodyClass;
        if(empty($requestClass)) return;
        $schema = $requestClass::schema();
        $this->validate($this->request->body(), $schema->toValidationArray());

    }

    /**
     * Validates the response
     *
     * @throws ValidationException
     */
    protected function validateResponse()
    {

        $responseClass = $this->responseBodyClass;
        if(empty($responseClass)) return;
        $schema = $responseClass::schema();
        $this->validate($this->response->body(), $schema->toValidationArray());

    }

    /**
     * Runs the action and returns the response
     *
     * @return SdkObject|SdkObjectCollection|array
     * @throws \Exception|SdkException
     */
    public function run()
    {

        $this->originalRequest = $this->request = $this->buildRequest();
        $this->runHooks($this->preSendHooks);

        try {

            $this->validateRequest();
            $this->originalResponse = $this->response = $this->context->getClient()->send($this->request);

        } catch (\Exception $e) {

            $this->clientException = $e;
            if($this->context->getClient()->hasResponse())
                $this->originalResponse = $this->response = $this->context->getClient()->getResponse();

            $this->runHooks($this->failureHooks);

            if(is_null($this->response)) throw $e;

        }

        if($this->response->statusCode() >= 300) {
            $exception = $this->getException($this->getExceptionKey($this->response));
            $exception->setDebugInfo($this->buildDebugInfo($this->request, $this->response));
            $this->clientException = $exception;
            $this->runHooks($this->failureHooks);
            throw $exception;
        }


        $this->runHooks($this->successHooks);
        $this->validateResponse();

        $responseBodyClass = $this->responseBodyClass;
        if(empty($responseBodyClass)) return $this->response->body();
        $this->responseBody =  $responseBodyClass::parse($this->response->body());

        return $this->responseBody;

    }

    /**
     * Converts an array to a tabbed JSON
     *
     * @param $array
     * @param int $tabs
     * @return string
     */
    private function serializeArrayToJSON($array, $tabs = 0) {
        $sep = str_pad("", $tabs, " ");
        $encoded = json_encode($array, JSON_PRETTY_PRINT);

        return $sep . join("\n$sep", explode(PHP_EOL, $encoded));
    }

    /**
     * Convert an array to a tabbed {key}: {value} list
     *
     * @param $array
     * @param int $tabs
     * @return string
     */
    private function arrayToInfoString($array, $tabs = 0) {
        $sep = str_pad("", $tabs, "");
        $output = "";
        foreach ($array as $key => $value) {
            $output .= "$sep$key: $value\n";
        }
        return $output;
    }

    /**
     * @param IRequest $request
     * @param IResponse $response
     * @return string
     */
    private function buildDebugInfo(IRequest $request, IResponse $response) {

        return "Request:\n\n" .
            "   Route: {$request->route()}\n" .
            "   Headers: \n" . $this->arrayToInfoString($request->headers(), 2) .
            "   Body: \n" . $this->serializeArrayToJSON($request->body(), 2) . "\n" .
            "   Query Parameters: \n" . $this->arrayToInfoString($request->query(), 2) . "\n\n\n" .
            "Response:\n\n" .
            "   Headers: \n" . $this->arrayToInfoString($response->headers(), 2) . "\n" .
            "   Status: {$response->statusCode()}\n".
            "   Body:\n" . $this->serializeArrayToJSON($response->body(), 2) . "\n";
    }



}