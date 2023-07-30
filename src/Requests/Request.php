<?php

namespace ZaiClient\Requests;

use RuntimeException;


class Request
{
    public $method;
    public $base_url;

    public function __construct(
        $method,
        $base_url
    ) {
        $this->method = $method;
        $this->base_url = $base_url;
    }

    public function get_path($client_id)
    {
        throw new RuntimeException("NotImplementedError");
    }

    public function get_payload($is_test)
    {
        throw new RuntimeException("NotImplementedError");
    }

    public function get_query_param()
    {
        throw new RuntimeException("NotImplementedError");
    }
}
