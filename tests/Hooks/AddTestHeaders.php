<?php

namespace SDK\Boilerplate\Tests\Hooks;


use SDK\Boilerplate\Hooks\PreSendHook;
use SDK\Boilerplate\RunState;

class AddTestHeaders extends PreSendHook
{

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