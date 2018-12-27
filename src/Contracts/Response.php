<?php

namespace SDK\Boilerplate\Contracts;


interface Response
{


    /**
     * Return the headers array
     *
     * @return array
     */
    public function headers();

    /**
     * Check if the response has the requested array
     *
     * @param $key
     * @return mixed
     */
    public function hasHeader($key);

    /**
     * Add an header value to the response
     *
     * @param array $headers Associative array of headers to add
     * @return $this
     */
    public function addHeaders(array $headers);

    /**
     * Remove an header from the response
     *
     * @param string $key
     * @return $this
     */
    public function removeHeader($key);

    /**
     * Return the status code
     *
     * @return integer
     */
    public function statusCode();

    /**
     * Returns the body
     *
     * @return array
     */
    public function body();

}