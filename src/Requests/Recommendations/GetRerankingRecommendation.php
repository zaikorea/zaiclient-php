<?php
namespace ZaiClient\Requests\Recommendations;

use InvalidArgumentException;
use ZaiClient\Configs\Config;
use ZaiClient\Requests\Recommendations\RecommendationRequest;

class GetRerankingRecommendation extends RecommendationRequest
{
    const DEFAULT_OFFSET = 0;
    const DEFAULT_RECOMMENDATION_TYPE = "category";

    public function __construct(
        $user_id,
        $item_ids,
        $request_options = array())
    {
        if (!is_array($item_ids)) {
            throw new InvalidArgumentException("item_ids must be an array");
        }

        if (!is_array($request_options)) {
            throw new InvalidArgumentException("request_options must be an array");
        }

        parent::__construct(
            $user_id,
            null,
            $item_ids,
            (array_key_exists("recommendation_type", $request_options)
                ? $request_options["recommendation_type"]
                : self::DEFAULT_RECOMMENDATION_TYPE),
            (array_key_exists("limit", $request_options)
                ? $request_options["limit"]
                : count($item_ids)),
            (array_key_exists("offset", $request_options)
                ? $request_options["offset"]
                : self::DEFAULT_OFFSET),
            (array_key_exists("recommendation_options", $request_options)
                ? $request_options["recommendation_options"]
                : null)
        );
    }

    public function getPath($client_id)
    {
        return sprintf(Config::ML_API_PATH_PREFIX, $client_id) . Config::RERANKING_RECOMMENDATION_PATH;
    }
}
