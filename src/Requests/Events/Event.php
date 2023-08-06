<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;
use JsonSerializable;

use ZaiClient\Configs\Config;
use ZaiClient\Utils\Validator;

class Event implements JsonSerializable
{
    /**
     * @var string $user_id ID of the user
     */
    private $user_id;

    /**
     * @var string $item_id ID of the item
     */
    private $item_id;

    /**
     * @var float $timestamp
     */
    private $timestamp;

    /**
     * @var string $event_type
     */
    private $event_type;

    /**
     * @var string $event_value
     */
    private $event_value;

    /**
     * @var string $from
     */
    private $from;

    /**
     * @var bool $is_zai_recommendation
     */
    private $is_zai_recommendation;

    /**
     * @var int $time_to_live
     */
    private $time_to_live;

    /**
     * @param string $user_id ID of the user
     * @param string $item_id ID of the item
     * @param float $timestamp
     * @param string $event_type
     * @param string $event_value
     * @param string $from
     * @param bool $is_zai_recommendation
     * @param int|null $time_to_live
     */
    public function __construct(
        $user_id,
        $item_id,
        $timestamp,
        $event_type,
        $event_value,
        $from,
        $is_zai_recommendation,
        $time_to_live
    ) {
        $this->user_id = Validator::validateString($user_id, 1, 500);
        $this->item_id = Validator::validateString($item_id, 1, 500, true);
        $this->timestamp = Validator::validateTimestamp($timestamp);
        $this->event_type = Validator::validateString($event_type, 1, 500);
        $this->event_value = Validator::validateString($event_value, 1, 500);
        $this->from = Validator::validateString($from, 1, 500, true);
        $this->is_zai_recommendation = Validator::validateBoolean($is_zai_recommendation, true);
        $this->time_to_live = Validator::validateInt($time_to_live, 0, 1000000000, true);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
