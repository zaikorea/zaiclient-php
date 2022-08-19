<?php

/**
 * SearchEvent
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Requests;

use ZaiKorea\ZaiClient\Requests\BaseEvent;
use ZaiKorea\ZaiClient\Requests\EventInBatch;
use ZaiKorea\ZaiClient\Configs\Config;
use ZaiKorea\ZaiClient\Exceptions\BatchSizeLimitExceededException;

class SearchEvent extends BaseEvent
{
    const EVENT_TYPE = 'search';
    const ITEM_ID = 'null';

    /**
     * SearchEvent accepts: 
     * - customer id
     * - single item_id or array of item_ids
     * - array of options
     * 
     * Here's an example of creating a View event using a single 
     * item_id or an array of item_ids * default request options to apply 
     * to each request: 
     * 
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $event_value = 'Blue Jeans'
     *     $view_event = new SearchEvent($user_id, $event_value);
     * 
     *     $user_id = '3f672ed3-4ea2-435f-91ff-ac32a3e4d1f1'
     *     $event_value = ['Blue Jeans', 'Chanel Purfume'];
     *     $options = ['timestamp'=> 1657197315];
     *     $view_event_batch = new SearchEvent($user_id, $event_values, $options);
     *
     * The SearchEvent class supports following options:
     *     - timesptamp: a custom timestamp given by the user, the user
     *                   can use this option to customize the timestamp
     *                   of the recorded event.
     * 
     * @param int|string $user_id
     * @param string|array $event_values
     * @param array $options
     */
    public function __construct($user_id, $search_query, $options = array())
    {
        // $page_type should not be an empty string
        if (!$search_query)
            throw new \InvalidArgumentException(
                sprintf(Config::NON_STR_ARG_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // $page_type should be a string
        if (!is_string($search_query))
            throw new \InvalidArgumentException(
                sprintf(Config::NON_STR_ARG_ERRMSG, self::class, __FUNCTION__, 2)
            );

        // change to array if $event_value is a single string
        $search_queries = array($search_query);

        // set timestamp to custom timestamp given by the user
        $this->setTimestamp(strval(microtime(true)));
        if (isset($options['timestamp']))
            $this->setTimestamp($options['timestamp']);

        $events = array();

        $tmp_timestamp = $this->getTimestamp();

        foreach ($search_queries as $search_query) {
            array_push($events, new EventInBatch(
                $user_id,
                self::ITEM_ID,
                $tmp_timestamp,
                self::EVENT_TYPE,
                $search_query
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
