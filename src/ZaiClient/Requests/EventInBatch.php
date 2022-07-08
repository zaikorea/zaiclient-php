<?php
/**
 * Event In Batch
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Requests;

/** 
 * @final
 */ 
class EventInBatch implements \JsonSerializable {
    protected $user_id;
    protected $item_id; // string or array
    protected $timestamp;
    protected $event_type;
    protected $event_value;

    /**
     * EventInBatch is a class equivalent to the json object.
     * It represents a single record for the Database.
     */
    public function __construct($user_id, $item_id, $timestamp, $event_type, $event_value) {
        $this->user_id = is_string($user_id) ? $user_id : strval($user_id);
        $this->item_id = is_string($item_id) ? $item_id : strval($item_id);
        $this->timestamp = strval($timestamp);
        $this->event_type = $event_type;
        $this->event_value = is_null($event_value) ? "null": strval($event_value);

        if (!(strlen($this->user_id) > 0 && strlen($this->user_id) <= 100))
            throw new \InvalidArgumentException('Length of user id must be between 1 and 100.');

        if (!(strlen($this->item_id) > 0 && strlen($this->item_id) <= 100))
            throw new \InvalidArgumentException('Length of user id must be between 1 and 100.');

        if (!(strlen($this->event_type) > 0 && strlen($this->event_type) <= 100))
            throw new \InvalidArgumentException('Length of event type must be between 1 and 100.');

    }

    function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
}
