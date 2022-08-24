<?php

/**
 * PageViewEvent
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;
use ZaiKorea\ZaiClient\Exceptions\BatchSizeLimitExceededException;

class PageViewEvent extends BaseEvent
{
    const EVENT_TYPE = 'page_view';
    const ITEM_ID = 'null';

    /**
     * PageViewEvent accepts: 
     * - customer id
     * - single event_value or array of event_values
     * - array of options
     * 
     * Here's an example of creating a View event using a single 
     * item_id or an array of item_ids * default request options to apply 
     * to each request: 
     * 
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $event_value = 'homepage'
     *     $view_event = new PageViewEvent($user_id, $event_value);
     * 
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $evant_values = ['homepage', 'category'];
     *     $options = ['timestamp'=> 1657197315];
     *     $view_event_batch = new PageViewEvent($user_id, $event_values, $options);
     *
     * The PageViewEvent class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     * 
     * @param int|string $user_id
     * @param string $page_type
     * @param array $options
     */
    public function __construct($user_id, $page_type, $options = array())
    {
        // $page_type should be a string
        if (!is_string($page_type))
            throw new \InvalidArgumentException(
                sprintf(Config::NON_STR_ARG_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // $page_type should be a non-empty string
        if (!$page_type)
            throw new \InvalidArgumentException(
                sprintf(Config::NON_STR_ARG_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // $page_type should not be an array (doesn't support batch)
        if (is_array($page_type))
            throw new \InvalidArgumentException(
                sprintf(Config::BATCH_ERRMSG, self::class)
            );

        // set timestamp to custom timestamp given by the user
        $this->setTimestamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimestamp($options['timestamp']);

        $event = new EventInBatch(
            $user_id,
            self::ITEM_ID,
            $this->getTimestamp(),
            self::EVENT_TYPE,
            $page_type
        );

        $this->setPayload($event);
    }
}
