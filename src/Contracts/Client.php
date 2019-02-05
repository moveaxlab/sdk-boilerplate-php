<?php

namespace SDK\Boilerplate\Contracts;


interface Client
{

    /**
     * Client constructor.
     * @param string $hostname
     * @param array $config
     */
    public function __construct($hostname, array $config = []);

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