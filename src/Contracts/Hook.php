<?php

namespace SDK\Boilerplate\Contracts;

interface Hook
{

    /**
     * Runs the hook
     *
     * @return Request|Response|null
     */
    public function run();

}