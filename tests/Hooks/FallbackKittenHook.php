<?php

namespace SDK\Boilerplate\Tests\Hooks;


use SDK\Boilerplate\Hooks\FailureHook;
use SDK\Boilerplate\RunState;

class FallbackKittenHook extends FailureHook
{

    public function run(RunState $state)
    {

        if($this->response) {
            dump('Received response with status code: ' . $this->response->statusCode());

            if($this->response->statusCode() === 404) {
                $this->response = new \SDK\Boilerplate\Response(200, [], [
                    'uuid' => '20916b08-f406-4785-ad6d-fb8cf5c3ae1e',
                    'color' => 'brown',
                    'name' => 'Fallback Kitten',
                    'date_of_birth' => '2018-05-11',
                    'owners' => [
                        [
                            'uuid' => '20916b08-f406-4785-ad6d-fb8cf5c3ae1e',
                            'first_name' => 'Fallback',
                            'last_name' => 'Owner',
                            'address' => '123 Cape Canaveral'
                        ]
                    ]
                ]);
            }
        }

        if($this->exception) {
            dump('Throwed exception of class: ' . get_class($this->exception));
        }

    }

}