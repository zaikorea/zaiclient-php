<?php
/**
 * RateEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;

class RateEvent extends BaseEvent {
    const EVENT_TYPE = 'rate';
    private $timestamp;

    /**
     *     $rate_actions = ['P1123456' => 0.5]
     *     $rate_actions = [
     *         'P1123456' => 0.5,
     *         'P6543210' => 0.7,
     *     ]
     *
     */
    public function __construct($customer_id, $rate_actions = array(), $options = array()) {
        $this->timestamp = strval(microtime(true));
        if (isset($options['timestamp']))
            $this->timestamp = $options['timestamp'];

        if (is_string($rate_actions))
            $rate_actions = array($rate_actions);

        $events = array();

        $tmp_timestamp = $this->timestamp;

        foreach ($rate_actions as $item_id => $value) {
            array_push($events, new EventInBatch(
                $customer_id,
                $item_id,
                $tmp_timestamp,
                self::EVENT_TYPE, // event value
                $value
            ));
            $tmp_timestamp += Config::EPSILON;
        }

        if (count($events) == 1)
            $this->setPayload($events[0]);
        else
            $this->setPayload($events);
    }
}
