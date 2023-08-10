<?php

/**
 * Recommendation request
 */

namespace ZaiClient\Requests\Recommendations;

use BadMethodCallException;
use ZaiClient\Configs\Config;
use ZaiClient\Requests\Recommendations\Recommendation;
use ZaiClient\Requests\Request;

class RecommendationRequest extends Request
{

    /**
     * @var Recommendation $payload
     */
    private $payload;

    public function __construct(
        $user_id,
        $item_id,
        $item_ids,
        $recommendation_type,
        $limit,
        $offset,
        $options
    ) {
        parent::__construct("POST", Config::ML_API_ENDPOINT);

        $this->payload = new Recommendation(
            $user_id,
            $item_id,
            $item_ids,
            $recommendation_type,
            $limit,
            $offset,
            $options
        );
    }


    /**
     * Get api path
     * @return string PATH to use for request
     */
    public function getPath($client_id = null)
    {

        throw new BadMethodCallException("NotImplementedError");
    }

    public function getPayload($is_test = null)
    {
        return $this->payload;
    }

    public function getQueryParams() // NOSONAR
    {
        throw new BadMethodCallException("NotImplementedError");
    }
}
