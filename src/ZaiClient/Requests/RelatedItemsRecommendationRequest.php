<?php
/**
 * Recommendation request
 */
namespace ZaiKorea\ZaiClient\Requests;
use ZaiKorea\ZaiClient\Requests\RecommendationRequest;
use ZaiKorea\ZaiClient\Configs\Config;

class RelatedItemsRecommendationRequest extends RecommendationRequest {
    CONST DEFAULT_RECOMMENDATION_TYPE = "product_detail_page";
    CONST DEFAULT_OFFSET = 0;
    CONST RECOMMENDER_PATH = "/related-items";

    public function __construct($item_id, $limit, $options = array()) {
        $this->item_id = $item_id;
        $this->limit = $limit;

        if (!is_array($options)) 
            throw new \InvalidArgumentException("options must be givent as an array. $options given instead.");

        $this->recommendation_type = isset($options['recommendation_type']) ? $options['recommendation_type'] : self::DEFAULT_RECOMMENDATION_TYPE;
        $this->offset = isset($options['offset']) ? $options['offset'] : self::DEFAULT_OFFSET;
    }

    /**
     * Get api path 
     * @return string PATH to use for request
     */
    public function getPath($client_id) {
        return sprintf(Config::ML_API_PATH_PREFIX . self::RECOMMENDER_PATH, $client_id);
    }

    /**
     * Get full URI with path 
     * @return string PATH to use for request
     */
    public function getURIPath($client_id) {
        return Config::ML_API_ENDPOINT . $this->getPath($client_id);
    }
}
