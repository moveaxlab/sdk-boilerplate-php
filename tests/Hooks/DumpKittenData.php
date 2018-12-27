<?php

namespace SDK\Boilerplate\Tests\Hooks;


use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\Response;
use SDK\Boilerplate\Contracts\SuccessHook;
use SDK\Boilerplate\RunState;

class DumpKittenData implements SuccessHook
{

    protected $action;

    protected $response;

    public function __construct(Action $action, Response &$response)
    {
        $this->action = $action;
        $this->response = $response;
    }

    public function run(RunState $state)
    {

        dump('Current color: ' . $this->response->body()['color']);
        dump('Original color: ' . $state->get('original-color', 'none'));

    }

}