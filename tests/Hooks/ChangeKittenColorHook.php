<?php

namespace SDK\Boilerplate\Tests\Hooks;

use SDK\Boilerplate\Hooks\SuccessHook;
use SDK\Boilerplate\RunState;

class ChangeKittenColorHook extends SuccessHook
{

    public function run(RunState $state)
    {

        $body = $this->response->body();
        $state->set('original-color', $body['color']);
        $body['color'] = 'brown';

        $this->response = new \SDK\Boilerplate\Response($this->response->statusCode(), $this->response->headers(), $body);

    }

}