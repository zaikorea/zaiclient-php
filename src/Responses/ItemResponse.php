<?php
namespace ZaiClient\Responses;

use ZaiClient\Requests\Items\Item;

class ItemResponse
{
    /**
     * @var array $items items returned from server
     */
    private $items;

    /**
     * @var int $count count of items returned from server
     */
     private $count;

     /**
      * @var float $timestamp timestamp from server
      */
    private $timestamp;

    /**
     * Set items
     * @param array[\ZaiClient\Requests\Items\Item] $items
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
     * Get Items
     * @return array Items from server
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get Count
     * @return int Count of items from server
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get Timestamp
     * @return float Timestamp from server
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function __toString()
    {
        return json_encode([
            'items' => $this->items,
            'count' => $this->count,
            'timestamp' => $this->timestamp,
        ]);
    }

}
