<?php

namespace SDK\Boilerplate\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Response;

class GuzzleHttpClient implements HttpClient
{

    /**
     * The Guzzle client instance
     */
    protected $client;

    /**
     * Hostname
     *
     * @var string
     */
    protected $hostname;

    /**
     * The response object
     *
     * @var Response
     */
    protected $response;

    /**
     * Default configuration parameters
     *
     * @var array
     */
    protected $defaultConfig = [
        'timeout' => 20,
        'verify_ssl_certs' => true
    ];

    /**
     * GuzzleHttpClient constructor.
     *
     * @param string $hostname
     * @param array $config
     */
    public function __construct($hostname, array $config = [])
    {

        $config = array_merge($this->defaultConfig, $config);
        $this->client = new Client($config);

    }

    /**
     * @inheritdoc
     *
     * @throws GuzzleException
     */
    public function send(Request $request)
    {
        $guzzleRequest = new \GuzzleHttp\Psr7\Request(
            $request->method(),
            $this->buildUrl($request->route(), $request->queryString()),
            $request->headers(),
            $request->body()
        );

        try {
            $guzzleResponse = $this->client->send($guzzleRequest);
        } catch(GuzzleException $e) {
            $guzzleResponse = $this->handleException($e);
        }

        return new Response(
            $guzzleResponse->getStatusCode(),
            $guzzleResponse->getHeaders(),
            json_decode($guzzleResponse->getBody()->getContents(), true)
        );
    }

    protected function buildUrl($route, $queryString)
    {

        $hostname = Str::endsWith($this->hostname, '/') ?
            Str::replaceLast('/', '', $this->hostname) :
            $this->hostname;

        $route = Str::startsWith($route, '/') ?
            Str::replaceFirst('/', '', $route)
            : $route;

        return "$hostname/$route$queryString";

    }

    /**
     * Handles a Guzzle Exception
     *
     * @param GuzzleException $e
     * @throws GuzzleException
     *
     * @return Response
     */
    protected function handleException(GuzzleException $e)
    {

        if($e instanceof RequestException) {

            if($e->hasResponse()) {
                $this->response = new Response(
                    $e->getResponse()->getStatusCode(),
                    $e->getResponse()->getHeaders(),
                    json_decode($e->getResponse()->getBody()->getContents(), true)
                );
            }

        }

        throw $e;
    }

    /**
     * @inheritdoc
     */
    public function hasResponse()
    {
        return !is_null($this->response);
    }

    public function getResponse()
    {
        return $this->response;
    }

}