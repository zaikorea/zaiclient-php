<?php

/**
 * CustomEvent
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
class CustomEvent extends BaseEvent
{

    /**
     * CustomEvent accepts:
     * - customer id
     * - 1D array with 'item_id' and 'value' keyword
     * - array of options
     *
     * Here's an example of creating a Custom event using an 1D associative
     * array with 'item_id' and 'value' as keywords, or a 2D sequential
     * array. When passing 2D array, each row should have 'item_id' and
     * 'value' keyword:
     *
     *     // imagine a customer can search for a specific item and
     *     // you want to log the search record, in this case there is
     *     // no need for a value in the action.
     *     $user_id =  '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1';
     *     $cutom_event_type = 'search';
     *     $custom_action = ['item_id'=> 'P1123456', 'value'=> null];
     *     $custom_event = new CustomEvent($user_id, $custom_event_type, $custom_action);
     *
     *     // imagine a customer can send a recommendation of a single item
     *     // to other users at once. And you want to log the items and
     *     // their prices.
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1';
     *     $cutom_event_type = 'send';
     *     $custom_actions =
     *         ['item_id' => 'P1123456', 'value' => 'USER_ID_1'],
     *         ['item_id' => 'P1123458', 'value' => 'USER_ID_2'],
     *         ['item_id' => 'P1123459', 'value' => 'USER_ID_3'],
     *     ];
     *     $custom_event_batch = new CustomEvent($user_id, $custom_event_type, $custom_actions);
     *
     * The CustomEvent class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     *
     * @param int|string $user_id
     * @param string $custom_event_type
     * @param string|array $item_ids
     * @param array $options
     */
    public function __construct(
        $user_id,
        $custom_event_type,
        $custom_actions = array(), $options = array())
    {
        // Validate if $custom_event_type is string
        if (!is_string($custom_event_type))
            throw new \InvalidArgumentException(
                sprintf(Config::NON_STR_ARG_ERRMSG, self::class, __FUNCTION__, 2)
            );
        if (!$custom_actions)
            throw new \InvalidArgumentException(
                sprintf(Config::EMPTY_ARR_ERRMSG, self::class, __FUNCTION__, 3)
            );
        // change to 2D array if $custom_actions is 1D array (action on single item)
        if (gettype(reset($custom_actions)) != 'array')
            $custom_actions = array($custom_actions);
        // Validate if $custom_event_type is sequential array
        if (array_keys($custom_actions) !== range(0, count($custom_actions) - 1))
            throw new \InvalidArgumentException(
                sprintf(Config::NON_SEQ_ARR_ERRMSG, self::class, __FUNCTION__, 3)
            );

        $this->setTimestamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimestamp($options['timestamp']);

        $events = array();

        $tmp_timestamp = $this->getTimestamp();

        foreach ($custom_actions as $custom_action) {
            if (array_keys($custom_action) != array('item_id', 'value')) {
                throw new \InvalidArgumentException(
                    sprintf(
                        Config::ARR_FORM_ERRMSG,
                        self::class,
                        __FUNCTION__,
                        3,
                        "[ ['item_id' => P12345, 'value' => int | null ] (1D array available if recording single custom action)"
                    )
                );
            }
            array_push($events, new EventInBatch(
                $user_id,
                $custom_action['item_id'],
                $tmp_timestamp,
                $custom_event_type,
                $custom_action['value']
            )
            );
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
