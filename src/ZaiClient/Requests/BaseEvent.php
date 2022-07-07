<?php
/**
 * BaseEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

class BaseEvent {

    /** 
     * @var $payload Payload for the http request to Recommender API.
     */ 
    protected $payload;

    public function setPayload($payload) {
        $this->payload = $payload;
    }

    public function getPayload() {
        return $this->payload;
    }
}
