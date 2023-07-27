<?php

/**
 * Recommendation response
 *
 * A friendly reference for deserializing array
 * https://github.com/cweiske/jsonmapper/issues/41
 */

namespace ZaiKorea\ZaiClient\Responses;

class RecommendationResponse
{

    /**
     * @var array $items array of strings ['ID_1', 'ID_2', ...]
     */
    private $items;

    /**
     * @var int $count number of items
     */
    private $count;

    /**
     * @var float $timestamp timestamp from server
     */
    private $timestamp;

    /**
     * @var array $metadata associative array
     */
    private $metadata;

    /**
     * Set Items with array of item_ids
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Set count
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Set timestamp
     * @param float $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Set Metadata with associative array
     * @param string $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = json_decode($metadata, $assoc = True);
        if (!is_array($this->metadata)) {
            $warningMessage = "Failed to parse the metadata to object, returning an empty object. metadata: " . $metadata;
            trigger_error($warningMessage, E_USER_WARNING);
            $this->metadata = array();
        }
    }

    /**
     * Get Array of items
     * @return int
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get count
     * @return int Count
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get Timestamp
     * @return float Timestamp from the server
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get Metadata
     * @return array Metadata from server
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    public function __toString()
    {
        return "RecommendationResponse{\n" .
            "\titems=" . implode(" | ", $this->items) . "\n" .
            "\tcount={$this->count}\n" .
            "\ttimestamp={$this->timestamp}\n" .
            "\tmetadata=" . print_r($this->metadata, true) . "\n" .
            "}\n";
    }
}
