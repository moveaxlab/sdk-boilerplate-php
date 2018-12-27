<?php

namespace SDK\Boilerplate\Traits;


use SDK\Boilerplate\Contracts\SuccessHook;

trait HasHooks
{




    /**
     * @inheritdoc
     */
    public function addPreSendHook(SuccessHook $hook)
    {

        $this->preSendHooks[] = $hook;

    }

    /**
     * @inheritdoc
     */
    public function getPreSendHooks()
    {

        return $this->preSendHooks;

    }

    /**
     * @inheritdoc
     */
    public function runPreSendHooks()
    {

        $this->runHooks($this->preSendHooks);

    }

    /**
     * @inheritdoc
     */
    public function addFailureHook(SuccessHook $hook)
    {

        $this->failureHooks[] = $hook;

    }

    /**
     * @inheritdoc
     */
    public function getFailureHooks()
    {

        return $this->failureHooks;

    }

    /**
     * @inheritdoc
     */
    public function runFailureHooks()
    {

        $this->runHooks($this->failureHooks);

    }

    /**
     * @inheritdoc
     */
    public function addSuccessHook(SuccessHook $hook)
    {

        $this->successHooks[] = $hook;

    }

    /**
     * @inheritdoc
     */
    public function getSuccessHooks()
    {

        return $this->successHooks;

    }

    /**
     * @inheritdoc
     */
    public function runSuccessHooks()
    {

        $this->runHooks($this->successHooks);

    }

    protected function runHooks(array $hooks)
    {

        foreach ($hooks as $hook) {
            (new $hook($this))->run();
        }

    }

}