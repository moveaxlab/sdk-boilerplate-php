<?php

namespace SDK\Boilerplate\Contracts;



use SDK\Boilerplate\RunState;

interface Hook
{

    /**
     * Runs the hook
     *
     * @param RunState $state The state of the current run
     *
     * @return Request|Response|null
     */
    public function run(RunState $state);

}