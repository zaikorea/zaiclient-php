<?php

/**
 * Recommendation request
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\RecommendationRequest;
use ZaiKorea\ZaiClient\Configs\Config;

class RelatedItemsRecommendationRequest extends RecommendationRequest
{
    const DEFAULT_RECOMMENDATION_TYPE = "product_detail_page";
    const DEFAULT_OFFSET = 0;
    const RECOMMENDER_PATH = "/related-items";

    public function __construct($item_id, $limit, $options = array())
    {
        if (is_null($item_id) || !(strlen($item_id) > 0 && strlen($item_id) <= 100))
            throw new \InvalidArgumentException('Length of item id must be between 1 and 100.');

        if (!(0 < $limit && $limit <= 1000000))
            throw new \InvalidArgumentException('Limit must be between 1 and 1000,000.');

        if (!is_array($options))
            throw new \InvalidArgumentException("Options must be given as an array.");
        if (isset($options['offset'])) {

            if (!(0 <= $options['offset'] && $options['offset'] <= 1000000))
                throw new \InvalidArgumentException('Offset must be between 0 and 1000,000.');
        }
        if (isset($options['recommendation_type'])) { // php tip! isset() returns false if the value of $options['recommendation_type'] is null
            if (!(0 < strlen($options['recommendation_type'] && strlen($options['recommendation_type']) <= 100)))
                throw new \InvalidArgumentException('Length of recommendation type must be between 1 and 100.');
        }

        $this->item_id = $item_id;
        $this->limit = $limit;

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
