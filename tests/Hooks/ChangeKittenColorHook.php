<?php

namespace SDK\Boilerplate\Tests\Hooks;


use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\Response;
use SDK\Boilerplate\Contracts\SuccessHook;
use SDK\Boilerplate\RunState;

class ChangeKittenColorHook implements SuccessHook
{

    protected $action;

    protected $response;

    public function __construct(Action $action, Response &$response)
    {
        $this->action = $action;
        $this->response = &$response;
    }

    public function run(RunState $state)
    {

        $body = $this->response->body();
        $state->set('original-color', $body['color']);
        $body['color'] = 'brown';

        $this->response = new \SDK\Boilerplate\Response($this->response->statusCode(), $this->response->headers(), $body);

    }

}