<?php
namespace ZaiClient\Requests\Recommendations;

use InvalidArgumentException;
use ZaiClient\Configs\Config;
use ZaiClient\Utils\Util;
use ZaiClient\Utils\Validator;
use ZaiClient\Requests\Recommendations\RecommendationRequest;

class GetUserRecommendation extends RecommendationRequest
{
    const DEFAULT_OFFSET = 0;
    const DEFAULT_RECOMMENDATION_TYPE = "homepage";

    /**
     * @param string|null $user_id
     * @param int $limit
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $limit,
        $request_options = array())
    {
        if (!is_array($request_options)) {
            throw new InvalidArgumentException("request_options must be an array");
        }

        parent::__construct(
            $user_id,
            null,
            null,
            (array_key_exists("recommendation_type", $request_options)
                ? $request_options["recommendation_type"]
                : self::DEFAULT_RECOMMENDATION_TYPE),
            Validator::validateInt($limit, 1, 10000, [
                "var_name" => "\$limit",
            ]),
            (array_key_exists("offset", $request_options)
                ? $request_options["offset"]
                : self::DEFAULT_OFFSET),
            (array_key_exists("recommendation_options", $request_options)
                ? $request_options["recommendation_options"]
                : null)
        );
    }

    public function getPath($client_id = null)
    {
        if (!is_string($client_id)) {
            throw new InvalidArgumentException("client_id must be a string");
        }

        return sprintf(Config::ML_API_PATH_PREFIX, $client_id) . Config::USER_RECOMMENDATION_PATH;
    }


}
