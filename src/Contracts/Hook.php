<?php

namespace SDK\Boilerplate\Contracts;



use SDK\Boilerplate\RunState;

interface Hook
{

    /**
     * Runs the hook
     *
     * @return Request|Response|null
     */
    public function run();

}