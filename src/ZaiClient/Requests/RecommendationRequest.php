<?php

/**
 * Recommendation request
 */

namespace ZaiKorea\ZaiClient\Requests;

abstract class RecommendationRequest implements \JsonSerializable
{

    /**
     * @var string $user_id ID used for getting user-recommendations
     */
    protected $user_id;

    /**
     * @var string $item_id ID used for getting related-items
     */
    protected $item_id;

    /**
     * @var string[] $item_id ID used for getting related-items
     */
    protected $item_ids;

    /**
     * @var int $limit number of items to fetch from recommender
     */
    protected $limit;

    /**
     * @var string $recommendation_type
     */
    protected $recommendation_type;

    /**
     * @var int $offset starting offset of the items to fetch from recommender
     */
    protected $offset;

    /**
     * Get api path 
     * @return string PATH to use for request
     */
    abstract public function getPath($client_id);

    /**
     * Get full URI with path 
     * @return string PATH to use for request
     */
    abstract public function getURIPath($client_id);

    /**
     * 
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * 
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * 
     * @return string
     */
    public function getRecommendationType()
    {
        return $this->recommendation_type;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
