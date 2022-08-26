<?php

/**
 * RateEvent
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;

/** 
 * @final
 */
class RateEvent extends BaseEvent
{
    const EVENT_TYPE = 'rate';

    /**
     * RateEvent accepts: 
     * - customer id
     * - 1D array with 'item_id' and 'value' keyword
     * - array of options
     * 
     * Here's an example of creating a Rate event using an 1D associative
     * array with 'item_id' and 'value' as keywords, or a 2D sequential 
     * array. When passing 2D array, each row should have 'item_id' and 
     * 'value' keyword:
     * 
     *     // customer has rated an item to 0.5.
     *     $user_id =  '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1';
     *     $rate_action = ['item_id => 'P1123456', 'value' => 0.5]
     *     $rate_event = new RateEvent($user_id, $rate_action);
     * 
     *     // customer has rated two items to 0.5, 0.8 respectively
     *     // at the same timestamp
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1';
     *     $rate_actions = 
     *         ['item_id' => 'P1123456', 'value' => 0.5],
     *         ['item_id' => 'P1123458', 'value' => 0.8]
     *     ];
     *     $rate_event_batch = new RateEvent($user_id, $rate_actions);
     * 
     * The RateEvent class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     * 
     * @param int|string $user_id
     * @param array $rate_actions
     * @param array $options
     * 
     */
    public function __construct($user_id, $rate_action = array(), $options = array())
    {
        // $rate_actions should not be an emtpy array
        if (!is_array($rate_action) || !$rate_action)
            throw new \InvalidArgumentException(
                sprintf(Config::EMPTY_ARR_ERRMSG, self::class, __FUNCTION__, 2)
            );
        // $rate_actions should be 1d array
        if (count($rate_action) != count($rate_action, COUNT_RECURSIVE)) 
            throw new \InvalidArgumentException(
                'Rate event doesn\'t support batch'
            );
        // change to 2D array if $rate_actions is 1D array (rate on single item) 
        if (gettype(reset($rate_action)) != 'array')
            $rate_actions = array($rate_action);
        // Validate if $rate_actions is sequential array
        if (array_keys($rate_actions) !== range(0, count($rate_actions) - 1))
            throw new \InvalidArgumentException(
                sprintf(Config::NON_SEQ_ARR_ERRMSG, self::class, __FUNCTION__, 2)
            );
        if (array_keys($rate_action) != array('item_id', 'value')) {
            throw new \InvalidArgumentException(
                sprintf(Config::ARR_FORM_ERRMSG, self::class, __FUNCTION__, 2, "[ ['item_id' => P12345, 'value' => 5.0] ] (1D array available if recording single rate action)")
            );
        }

        $this->setTimestamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimestamp($options['timestamp']);

        $event = new EventInBatch(
            $user_id,
            $rate_action['item_id'],
            $this->getTimestamp(),
            self::EVENT_TYPE,
            $rate_action['value']
        );
        $this->setPayload($event);
    }
}
