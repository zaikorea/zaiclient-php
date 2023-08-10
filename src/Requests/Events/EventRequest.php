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
    /**
     * @var Event | array[Event]
     */
    private $payload;
    private $timestamp;

    /**
     * @param string $user_id
     * @param array[string] $item_ids
     * @param float $timestamp
     * @param string $event_type
     * @param array[string|int|float] $event_values
     * @param array[string] $from_values
     * @param array[bool] $is_zai_recommendations
     */
    public function __construct(
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
        $tmp_timestamp = is_null($timestamp) ? microtime(true) : $timestamp;
        $this->timestamp = $tmp_timestamp;

        parent::__construct("POST", config::COLLECTOR_API_ENDPOINT);

        for ($i = 0; $i < count($item_ids); $i++) {
            array_push(
                $events,
                new Event(
                    $user_id,
                    $item_ids[$i],
                    $tmp_timestamp,
                    $event_type,
                    substr($event_values[$i], 0, 500),
                    is_null($from_values[$i]) ? null : substr($from_values[$i], 0, 500),
                    $is_zai_recommendations[$i],
                    null // TODO: Events don't have to set time_to_live
                )
            );
            $tmp_timestamp += config::EPSILON;
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

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    protected function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getPath($client_id)
    {
        return config::EVENTS_API_PATH;
    }

    public function getPayload($is_test = false)
    {
        if ($is_test) {
            if (is_array($this->payload)) {
                foreach ($this->payload as &$event) {
                    $event->setTimeToLive(config::TEST_EVENT_TIME_TO_LIVE);
                }
            } else {
                $this->payload->setTimeToLive(config::TEST_EVENT_TIME_TO_LIVE);
            }
        }
        return $this->payload;
    }

    public function getQueryParams()
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
