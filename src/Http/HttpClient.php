<?php

namespace SDK\Boilerplate\Http;


use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Contracts\Response;

interface HttpClient
{

    /**
     * Send the request
     *
     * @param $request
     * @return Response
     */
    public function send(Request $request);

    /**
     * Check if the client has an associated response
     *
     * @return bool
     */
    public function hasResponse();

    /**
     * Returns the response
     *
     * @return Response
     */
    public function getResponse();

}