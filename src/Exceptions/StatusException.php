<?php


namespace SDK\Boilerplate\Exceptions;


use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Contracts\Response;
use Throwable;

class StatusException extends \Exception
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Action
     */
    protected $action;

    /**
     * StatusException constructor.
     * @param Action $action
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Action $action, $message = "", $code = 0, Throwable $previous = null)
    {

        parent::__construct($message, $code, $previous);
    }

    /**
     * Set the executed request
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the request
     *
     * @return Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the returned response
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Check if the exception has an associated response
     *
     * @return bool
     */
    public function hasResponse()
    {
        return !is_null($this->response);
    }

    /**
     * Return the response
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return the action
     *
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
    }

}