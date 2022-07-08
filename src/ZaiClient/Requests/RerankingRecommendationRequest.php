<?php
/**
 * Recommendation request
 */
namespace ZaiKorea\ZaiClient\Requests;
use ZaiKorea\ZaiClient\Requests\RecommendationRequest;
use ZaiKorea\ZaiClient\Configs\Config;

class RerankingRecommendationRequest extends RecommendationRequest {
    CONST DEFAULT_RECOMMENDATION_TYPE = "all_products_page";
    CONST DEFAULT_OFFSET = 0;
    CONST RECOMMENDER_PATH = "/reranking";
    

    /**
     * Accepts a array of item_ids, options
     * @param string $user_id
     * @param string $item_ids
     */
    public function __construct($user_id, $item_ids, $limit, $options = array()) {
        if (!is_array($item_ids))
            throw new \InvalidArgumentException("item_ids must be an array");
        
        if (!(0 < count($item_ids) && count($item_ids) <= 1000000))
            throw new \InvalidArgumentException("Length of item_ids must be between 1 and 1000,000.");
        
        foreach ($item_ids as $item_id) {
            if (!(strlen($item_id) > 0 && strlen($item_id) <= 100))
                throw new \InvalidArgumentException('Length of item id must be between 1 and 100.');
        }

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
        $this->item_ids = $item_ids; // This should be an array
        $this->limit = $limit;

        if (!is_array($options)) 
            throw new \InvalidArgumentException("options must be given as an array.");

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
