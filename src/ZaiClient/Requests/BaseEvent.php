<?php
/**
 * BaseEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

class BaseEvent {
    protected $payload;

    public function setPayload($payload) {
        $this->payload = $payload;
    }

    public function getPayload() {
        return $this->payload;
    }
}
