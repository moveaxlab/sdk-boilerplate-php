<?php

namespace SDK\Boilerplate\Tests\Hooks;


use SDK\Boilerplate\Hooks\SuccessHook;
use SDK\Boilerplate\RunState;

class DumpKittenData extends SuccessHook
{

    public function run(RunState $state)
    {

        dump('Current color: ' . $this->response->body()['color']);
        dump('Original color: ' . $state->get('original-color', 'none'));

    }

}