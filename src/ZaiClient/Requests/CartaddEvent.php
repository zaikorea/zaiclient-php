<?php

/**
 * CartaddEvent
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;
use ZaiKorea\ZaiClient\Exceptions\BatchSizeLimitExceededException;

/** 
 * @final
 */
class CartaddEvent extends BaseEvent
{
    const EVENT_TYPE = 'cartadd';
    const EVENT_VALUE = 'null';

    /**
     * CartaddEvent accepts: 
     * - customer id
     * - single item_id or array of item_ids
     * - array of options
     * 
     * Here's an example of creating a Cartadd event using a single 
     * item_id or an array of item_ids * default request options to apply 
     * to each request: 
     * 
     *     $customer_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $item_id = 'P1123456'
     *     $cartadd_event = new CartaddEvent($customer_id, $item_id);
     * 
     *     $customer_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $item_id = ['P11234567', 'P11234567'];
     *     $options = ['timestamp'=> 1657197315];
     *     $cartadd_event_batch = new CartaddEvent($customer_id, $item_ids, $options);
     *
     * The Cartadd class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     * 
     * @param int|string $customer_id
     * @param string|array $item_id
     * @param array $options
     */
    public function __construct($customer_id, $item_id, $options = array())
    {
        // $item_id should not be an emtpy string
        if (!$item_id)
            throw new \InvalidArgumentException(
                'Length of item id must be between 1 and 100.'
            );
        
        // $page_type should not be an array (doesn't support batch)
        if (!is_string($item_id))
            throw new \InvalidArgumentException(
                sprintf(Config::EMPTY_STR_ARG_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // change to array if $item_id is a single string
        if (is_string($item_id))
            $item_ids = array($item_id);

        $this->setTimestamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimestamp($options['timestamp']);

        $events = array();

        $tmp_timestamp = $this->getTimestamp();

        foreach ($item_ids as $item_id) {
            array_push($events, new EventInBatch(
                $customer_id,
                $item_id,
                $tmp_timestamp,
                self::EVENT_TYPE,
                self::EVENT_VALUE
            ));
            $tmp_timestamp += Config::EPSILON;
        }

        if (count($events) > 50)
            throw new BatchSizeLimitExceededException(count($events));

        if (count($events) == 1)
            $this->setPayload($events[0]);
        else
            $this->setPayload($events);
    }
}
