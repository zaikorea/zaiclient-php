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
     * @var string $options jsonString of options
     */
    protected $options;

    /**
     * Get api path
     * @return string PATH to use for request
     */
    abstract public function getPath($client_id);

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

    /**
     *
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Utility function
     *
     * @param array $arr
     * @return boolean
     */
    public function isAssoc(array $arr)
    {
        if (array() === $arr)
            return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
