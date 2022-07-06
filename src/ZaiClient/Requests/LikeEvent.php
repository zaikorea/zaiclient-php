<?php
/**
 * LikeEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;

class LikeEvent extends BaseEvent {
    const EVENT_TYPE = 'like';
    const EVENT_VALUE = 1;
    private $timestamp;

    /**
     *     $item_id = 'P1123456'
     *     $item_id = ['P11234567', 'P11234567'] // TODO: This could be misleading
     *
     */
    public function __construct($customer_id, $item_ids, $options = array()) {
        $this->timestamp = strval(microtime(true));
        if (isset($options['timestamp']))
            $this->timestamp = $options['timestamp'];

        if (is_string($item_ids))
            $item_ids = array($item_ids);

        $events = array();

        $tmp_timestamp = $this->timestamp;

        foreach ($item_ids as $item_id) {
            array_push($events, new EventInBatch(
                $customer_id,
                $item_id,
                $tmp_timestamp,
                self::EVENT_TYPE, // event value
                self::EVENT_VALUE,
            ));
            $tmp_timestamp += Config::EPSILON;
        }

        if (count($events) == 1)
            $this->setPayload($events[0]);
        else
            $this->setPayload($events);
    }
}
