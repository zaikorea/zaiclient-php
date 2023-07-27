<?php

/**
 * LikeEvent
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
class LikeEvent extends BaseEvent
{
    const EVENT_TYPE = 'like';
    const EVENT_VALUE = 'null';

    /**
     * LikeEvent accepts:
     * - customer id
     * - single item_id or array of item_ids
     * - array of options
     *
     * Here's an example of creating a Like event using a single
     * item_id or an array of item_ids * default request options to apply
     * to each request:
     *
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $item_id = 'P1123456'
     *     $like_event = new LikeEvent($user_id, $item_id);
     *
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $item_id = ['P11234567', 'P11234567'];
     *     $options = ['timestamp'=> 1657197315];
     *     $like_event_batch = new LikeEvent($user_id, $item_ids, $options);
     *
     * The LikeEvent class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     *
     * @param int|string $user_id
     * @param string|array $item_ids
     * @param array $options
     */
    public function __construct($user_id, $item_id, $options = array())
    {
        // $item_id should not be an emtpy array
        if (!$item_id)
            throw new \InvalidArgumentException(
                'Length of item id must be between 1 and 100.'
            );
        // $page_type should not be an array (doesn't support batch)
        if (is_array($item_id))
            throw new \InvalidArgumentException(
                sprintf(Config::BATCH_ERRMSG, self::class)
            );

        // set timestamp to custom timestamp given by the user
        $this->setTimestamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimestamp($options['timestamp']);

        $event = new EventInBatch(
            $user_id,
            $item_id,
            $this->getTimestamp(),
            self::EVENT_TYPE,
            self::EVENT_VALUE
        );

        $this->setPayload($event);
    }
}
