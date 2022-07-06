<?php
/**
 * PurchaseEvent
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;

class PurchaseEvent extends BaseEvent {
    private $timestamp;
    

    /**
     *     $orders = array(
     *         'item_id1'=> [ 
     *             'price' => 10000, 
     *             'count' => 3 
     *         ],
     *         'item_id2'=> [
     *             'price' => 10001, 
     *             'count' => 5
     *         ],
     *     )
     *
     */
    public function __construct($customer_id, $orders=array(), $options = array()) {
        $this->timestamp = strval(microtime(true));
        if (isset($options['timestamp']))
            $this->timestamp = $options['timestamp'];

        $events = array();

        $tmp_timestamp = $this->timestamp;
        foreach ($orders as $item_id => $order_spec) {
            for ($i = 0; $i < $order_spec['count']; $i++) {
                array_push($events, new EventInBatch(
                    $customer_id,
                    $item_id,
                    $tmp_timestamp,
                    Config::PURCHASE_EVENT_TYPE, // event value
                    $order_spec['price']
                ));
                $tmp_timestamp += Config::EPSILON;
            }
        }

        if (count($events) == 1)
            $this->setPayload($events[0]);
        else
            $this->setPayload($events);
    }
}
