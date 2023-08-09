<?php

/**
 * Recommendation request
 */

namespace ZaiClient\Requests\Recommendations;

use JsonSerializable;
use RuntimeException;
use ZaiClient\Requests\Recommendations\Recommendation;

class RecommendationRequest
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
    public function getPath($client_id)
    {

        throw new RuntimeException("NotImplementedError");
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getQueryParam()
    {
        throw new RuntimeException("NotImplementedError");
    }
}
