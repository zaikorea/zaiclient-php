<?php
/**
 * Event In Batch
 */

namespace ZaiKorea\ZaiClient\Requests;

class EventInBatch implements \JsonSerializable {
    protected $user_id;
    protected $item_id; // string or array
    protected $timestamp;
    protected $event_type;
    protected $event_value;

    public function __construct($user_id, $item_id, $timestamp, $event_type, $event_value) {
        $this->user_id = $user_id;
        $this->item_id = $item_id;
        $this->timestamp = $timestamp;
        $this->event_type = $event_type;
        $this->event_value = $event_value;
    }

    function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
}
