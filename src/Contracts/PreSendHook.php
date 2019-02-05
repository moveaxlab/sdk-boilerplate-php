<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\Context;

interface PreSendHook extends Hook
{

    /**
     * Hook constructor.
     * @param Context $context
     * @param Request $request
     */
    function __construct(Context $context, Request $request);

}