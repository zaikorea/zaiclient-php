<?php

namespace ZaiClient\Requests;

use BadMethodCallException;
use JsonSerializable;


class Request
{
    private $method;
    private $base_url;

    public function __construct(
        $method,
        $base_url
    ) {
        $this->method = $method;
        $this->base_url = $base_url;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param string $client_id
     * @return string PATH to use for request
     */
    public function getPath($client_id = null) // NOSONAR
    {
        throw new BadMethodCallException("NotImplementedError");
    }

    /**
     * @param string $client_id
     * @return JsonSerializable payload in json to use for request
     */
    public function getPayload($is_test) // NOSONAR
    {
        throw new BadMethodCallException("NotImplementedError");
    }

    /**
     * @param string $client_id
     * @return array payload in json to use for request
     */
    public function getQueryParams() // NOSONAR
    {
        throw new BadMethodCallException("NotImplementedError");
    }
}
