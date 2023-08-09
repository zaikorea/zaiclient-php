<?php

/**
 * Recommendation request
 */

namespace ZaiClient\Requests;

use ZaiClient\Requests\RecommendationRequest;
use ZaiClient\Configs\Config;

class UserRecommendationRequest extends RecommendationRequest
{
    const DEFAULT_RECOMMENDATION_TYPE = 'homepage';
    const DEFAULT_OFFSET = 0;
    const DEFAULT_OPTIONS = null;
    const RECOMMENDER_PATH = '/user-recommendations';


    public function __construct($user_id, $limit, $options = array())
    {
        if (!(is_null($user_id) || strlen($user_id) > 0 && strlen($user_id) <= 500))
            throw new \InvalidArgumentException('Length of user id must be between 1 and 500.');
        if (is_null($limit) || !(0 <= $limit && $limit <= 10000))
            throw new \InvalidArgumentException('Limit must be between 0 and 10,000.');
        if (!is_array($options))
            throw new \InvalidArgumentException('Options must be given as an array.');
        if (isset($options['offset'])) {
            if (!(0 <= $options['offset'] && $options['offset'] <= 10000))
                throw new \InvalidArgumentException('Offset must be between 0 and 10,000.');
        }
        if (isset($options['recommendation_type'])) { // php tip! isset() returns false if the value of $options['recommendation_type'] is null
            if (!(0 < strlen($options['recommendation_type'] && strlen($options['recommendation_type']) <= 500)))
                throw new \InvalidArgumentException('Length of recommendation type must be between 1 and 500.');
        }
        if (isset($options['recommendation_options'])) {
            if (!is_array($options['recommendation_options']) || !$this->isAssociativeArray($options['recommendation_options'])) {
                throw new \InvalidArgumentException("\$options['recommendation_options'] must be an associative array.");
            }
            if (strlen(json_encode($options['recommendation_options'])) > 1000) {
                throw new \InvalidArgumentException("\$options['recommendation_options'] must be less than or equal to 1000 when converted to string");
            }
        }

        $this->user_id = $user_id;
        $this->limit = $limit;

        $this->recommendation_type = isset($options['recommendation_type']) ? $options['recommendation_type'] : self::DEFAULT_RECOMMENDATION_TYPE;
        $this->offset = isset($options['offset']) ? $options['offset'] : self::DEFAULT_OFFSET;
        $this->options = isset($options['recommendation_options']) ? json_encode($options['recommendation_options']) : self::DEFAULT_OPTIONS;
    }

    /**
     * Get api path
     * @return string PATH to use for request
     */
    public function getPath($client_id)
    {
        return sprintf(Config::ML_API_PATH_PREFIX . self::RECOMMENDER_PATH, $client_id);
    }

}
