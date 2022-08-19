<?php

/**
 * PurchaseEvent
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
class PurchaseEvent extends BaseEvent
{
    const EVENT_TYPE = 'purchase';

    /**
     * PurchaseEvent accepts: 
     * - customer id
     * - 1D array with 'item_id' and 'value' keyword
     * - array of options
     * 
     * Here's an example of creating a Purchase event using an 1D associative
     * array with 'item_id', 'price', and 'count' as keywords, or a 2D sequential 
     * array. When passing 2D array, each row should have 'item_id' and 
     * 'value' keyword:
     * 
     *     // imagine a customer can search for a specific item and
     *     // you want to log the search record, in this case there is
     *     // no need for a value in the action.
     *     $user_id =  '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1';
     *     $order = [ 'item_id' => 'P123431', 'price' => 100001, 'count' => 5]
     *     $purchase_event = new PurchaseEvent($user_id, $order);
     * 
     *     // imagine a customer can send a recommendation of multiple 
     *     // items to another user at once.
     *     // And you want to log the items and their prices.
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1';
     *     $orders = [
     *         [ 
     *             'item_id' => 'P123431',
     *             'price' => 10000, 
     *             'count' => 3 
     *         ],
     *         [ 
     *             'item_id' => 'P123435',
     *             'price' => 10001, 
     *             'count' => 5
     *         ]
     *     ];
     *     $purchase_event_batch = new PurchaseEvent($user_id, $orders);
     * 
     * The PurchaseEvent class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     * 
     * @param int|string $user_id
     * @param array $item_ids
     * @param array $options
     * 
     */
    public function __construct($user_id, $orders = array(), $options = array())
    {
        // $orders should not be an emtpy array
        if (!$orders)
            throw new \InvalidArgumentException(
                sprintf(Config::EMPTY_ARR_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // change to 2D array if $orders is 1D array (order on single item) 
        if (gettype(reset($orders)) != 'array')
            $orders = array($orders);

        // Validate if $order is sequential array
        if (array_keys($orders) !== range(0, count($orders) - 1))
            throw new \InvalidArgumentException(
                sprintf(Config::NON_SEQ_ARR_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // set timestamp to custom timestamp given by the user
        $this->setTimeStamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimeStamp($options['timestamp']);

        $events = array();

        $tmp_timestamp = $this->getTimestamp();

        foreach ($orders as $order_spec) {
            if (array_keys($order_spec) != array('item_id', 'price', 'count')) {
                throw new \InvalidArgumentException(
                    sprintf(Config::ARR_FORM_ERRMSG, self::class, __FUNCTION__, 2, "[ ['item_id' => P12345, 'price' => 50000, 'count' => 3] ] (1D array available if recording single order)")
                );
            }

            for ($i = 0; $i < $order_spec['count']; $i++) {
                array_push($events, new EventInBatch(
                    $user_id,
                    $order_spec['item_id'],
                    $tmp_timestamp,
                    self::EVENT_TYPE,
                    $order_spec['price']
                ));
                $tmp_timestamp += Config::EPSILON;
            }
        }

        if (count($events) > 50)
            throw new BatchSizeLimitExceededException(count($events));

        if (count($events) == 1)
            $this->setPayload($events[0]);
        else
            $this->setPayload($events);
    }
}
