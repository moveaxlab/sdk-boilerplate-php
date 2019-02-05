<?php

namespace SDK\Boilerplate\Contracts;


interface Request
{

    /**
     * Returns the request method
     *
     * @return string
     */
    public function method();

    /**
     * Returns the request url
     *
     * @return string
     */
    public function route();

    /**
     * Return the headers array
     *
     * @return array
     */
    public function headers();

    /**
     * Check if the request has the requested array
     *
     * @param $key
     * @return mixed
     */
    public function hasHeader($key);

    /**
     * Add an header value to the request
     *
     * @param array $headers Associative array of headers to add
     * @return $this
     */
    public function addHeaders(array $headers);

    /**
     * Remove an header from the request
     *
     * @param string $key
     * @return $this
     */
    public function removeHeader($key);

    /**
     * Return the query string
     *
     * @return string
     */
    public function query();

    /**
     * Get the query parameters
     *
     * @return array
     */
    public function queryString();

    /**
     * Add a query parameters as associative array
     *
     * @param array $parameters
     * @return $this
     */
    public function addQueryParameters(array $parameters);

    /**
     * Remove a query parameter
     *
     * @param string $key
     * @return $this
     */
    public function removeQueryParameter($key);

    /**
     * Returns the body
     *
     * @return array
     */
    public function body();


}