<?php
/**
 * CustomEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;

class CustomEvent extends BaseEvent {
    const EVENT_TYPE = 'rate';
    private $timestamp;

    /**
     *
     *     $custom_actions = ['P1123456' => 0.5]
     *     $custom_actions = [
     *         'P1123456' => 4,
     *         'P6543210' => 5,
     *     ]
     *
     */
    public function __construct(
        $customer_id, 
        $custom_event, 
        $custom_actions = array(), 
        $options = array()
    ) {
        $this->timestamp = strval(microtime(true));
        if (isset($options['timestamp']))
            $this->timestamp = $options['timestamp'];

        if (is_string($custom_actions))
            $custom_actions = array($custom_actions);

        $events = array();

        $tmp_timestamp = $this->timestamp;

        foreach ($custom_actions as $item_id => $value) {
            array_push($events, new EventInBatch(
                $customer_id,
                $item_id,
                $tmp_timestamp,
                $custom_event,
                !is_null($value) ? $value : 1
            ));
            $tmp_timestamp += Config::EPSILON;
        }

        if (count($events) == 1)
            $this->setPayload($events[0]);
        else
            $this->setPayload($events);
    }
}
