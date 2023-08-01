<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;
use ZaiClient\Configs\Config;
use ZaiClient\Exceptions\BatchSizeLimitExceededException;
use ZaiClient\Exceptions\EmptyBatchException;
use ZaiClient\Requests\Request;
use ZaiClient\Requests\Events\Event;

class EventRequest extends Request
{

    public $user_id;
    public $item_ids;
    public $timestamp;
    public $event_type;
    public $event_values;
    public $from_values;
    public $is_zai_recommendations;
    public $_timestamp;
    public $payload;

    /**
     * @param string $user_id
     * @param array[string] $item_ids
     * @param float $timestamp
     * @param string $event_type
     * @param array[string] $event_values
     * @param array[string] $from_values
     * @param array[bool] $is_zai_recommendations
     */
    function __construct(
        $user_id,
        $item_ids,
        $timestamp,
        $event_type,
        $event_values,
        $from_values,
        $is_zai_recommendations
    ) {

        // Only validate whether the parameters are array (The element types are checked)
        $this->validate($item_ids, $event_values, $from_values, $is_zai_recommendations);

        $events = [];
        $tmp_timestamp = $timestamp;
        $this->_timestamp = $timestamp;
        parent::__construct("POST", config::COLLECTOR_API_ENDPOINT);
        $i = 0;
        foreach (array_combine($item_ids, $event_values) as $item_id => $event_value) {
            if ($i < count($from_values)) {
                $from_value = $from_values[$i];
            } else {
                $from_value = null;
            }
            if ($i < count($is_zai_recommendations)) {
                $is_zai_recommendation = $is_zai_recommendations[$i];
            } else {
                $is_zai_recommendation = null;
            }
            array_push(
                $events,
                new Event(
                    $user_id,
                    $item_id,
                    $tmp_timestamp,
                    $event_type,
                    substr($event_value, 0, 500),
                    substr($from_value, 0, 500),
                    $is_zai_recommendation,
                    null
                )
            );
            $tmp_timestamp += config::EPSILON;
            $i += 1;
        }
        if (count($events) > config::BATCH_REQUEST_CAP) {
            throw new BatchSizeLimitExceededException();
        }
        if (count($events) == 0) {
            throw new EmptyBatchException();
        }
        if (count($events) == 1) {
            $this->payload = $events[0];
        } else {
            $this->payload = $events;
        }
    }

    function getTimestamp()
    {
        return $this->_timestamp;
    }

    function getPath($client_id)
    {
        return config::EVENTS_API_PATH;
    }

    function getPayload($is_test = false)
    {
        if ($is_test) {
            if (is_array($this->payload)) {
                foreach ($this->payload as &$event) {
                    $event["time_to_live"] = config::TEST_EVENT_TIME_TO_LIVE;
                }
            } else {
                $this->payload["time_to_live"] = config::TEST_EVENT_TIME_TO_LIVE;
            }
        }
        return $this->payload;
    }

    function getQueryParam()
    {
        return [];
    }

    private function validate(
        $item_ids,
        $event_values,
        $from_values,
        $is_zai_recommendations
    ) {
        if (!is_array($item_ids)) {
            throw new InvalidArgumentException("item_ids must be an array");
        }

        if (!is_array($event_values)) {
            throw new InvalidArgumentException("event_values must be an array");
        }

        if (!is_array($from_values)) {
            throw new InvalidArgumentException("from_values must be an array");
        }
        if (!is_array($is_zai_recommendations)) {
            throw new InvalidArgumentException("is_zai_recommendations must be an array");
        }

        // Check if the length of the arrays of all 4 arrays are the same
        if (
            count($item_ids) != count($event_values) ||
            count($item_ids) != count($from_values) ||
            count($item_ids) != count($is_zai_recommendations)
        ) {
            throw new InvalidArgumentException(
                "item_ids, event_values, from_values, is_zai_recommendations must have the same length"
            );
        }
    }
}
