<?php

/**
 * Recommendation request
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\RecommendationRequest;
use ZaiKorea\ZaiClient\Configs\Config;

class UserRecommendationRequest extends RecommendationRequest
{
    const DEFAULT_RECOMMENDATION_TYPE = 'homepage';
    const DEFAULT_OFFSET = 0;
    const RECOMMENDER_PATH = '/user-recommendations';


    public function __construct($user_id, $limit, $options = array())
    {
        if (!(is_null(null) || strlen($user_id) > 0 && strlen($user_id) <= 100))
            throw new \InvalidArgumentException('Length of user id must be between 1 and 100.');

        if (!(0 < $limit && $limit <= 1000000))
            throw new \InvalidArgumentException('Limit must be between 1 and 1000,000.');

        if (isset($options['offset'])) {
            if (!(0 <= $options['offset'] && $options['offset'] <= 1000000))
                throw new \InvalidArgumentException('Offset must be between 0 and 1000,000.');
        }
        if (isset($options['recommendation_type'])) {
            if ($options['recommendation_type'] == null || !(0 < strlen($options['recommendation_type'] && strlen($options['recommendation_type']) <= 100)))
                throw new \InvalidArgumentException('Length of recommendation type must be between 1 and 100.');
        }

        $this->user_id = $user_id;
        $this->limit = $limit;

        if (!is_array($options))
            throw new \InvalidArgumentException('options must be givent as an array. $options given instead.');

        $this->recommendation_type = isset($options['recommendation_type']) ? $options['recommendation_type'] : self::DEFAULT_RECOMMENDATION_TYPE;
        $this->offset = isset($options['offset']) ? $options['offset'] : self::DEFAULT_OFFSET;
    }

    /**
     * Get api path 
     * @return string PATH to use for request
     */
    public function getPath($client_id)
    {
        return sprintf(Config::ML_API_PATH_PREFIX . self::RECOMMENDER_PATH, $client_id);
    }

    /**
     * Get full URI with path 
     * @return string PATH to use for request
     */
    public function getURIPath($client_id)
    {
        return Config::ML_API_ENDPOINT . $this->getPath($client_id);
    }
}
