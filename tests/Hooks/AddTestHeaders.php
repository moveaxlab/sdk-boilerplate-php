<?php

namespace SDK\Boilerplate\Tests\Hooks;


use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Contracts\PreSendHook;
use SDK\Boilerplate\RunState;

class AddTestHeaders implements PreSendHook
{

    protected $action;
    protected $request;

    public function __construct(Action $action, Request &$request)
    {
        $this->action = $action;
        $this->request = &$request;
    }

    public function run(RunState $state)
    {
        $this->request = new \SDK\Boilerplate\Request(
          $this->request->method(),
          $this->request->url(),
          $this->request->query(),
          $this->request->headers() + ['Test-Header' => 'Dummy-data'],
          $this->request->body()
        );
    }

}