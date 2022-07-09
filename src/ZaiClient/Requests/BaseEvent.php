<?php

/**
 * BaseEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

class BaseEvent
{

    /** 
     * @var $payload Payload for the http request to Recommender API.
     */
    protected $payload;

    /** 
     * @var $timestamp Timestamp of the event happening
     */
    protected $timestamp;

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
