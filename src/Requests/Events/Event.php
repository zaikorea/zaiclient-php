<?php
namespace ZaiClient\Requests\Events;

use JsonSerializable;

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
        $this->user_id = Validator::validateString($user_id, 1, 500, [
            "var_name" => "\$user_id"
        ]);
        $this->item_id = Validator::validateString($item_id, 1, 100, [
            "nullable" => true,
            "var_name" => "\$item_id"
        ]);
        $this->timestamp = Validator::validateTimestamp($timestamp, [
            "var_name" => "\$timestamp"
        ]);
        $this->event_type = Validator::validateString($event_type, 1, 500, [
            "var_name" => "\$event_type"
        ]);
        $this->event_value = Validator::validateString($event_value, 1, 500, [
            "var_name" => "\$event_value"
        ]);
        $this->from = Validator::validateString($from, 1, 500, [
            "nullable" => true,
            "var_name" => "\$from"
        ]);
        $this->is_zai_recommendation = Validator::validateBoolean(
            $is_zai_recommendation,
            [
                "nullable" => true,
                "var_name" => "is_zai_recommendation"
            ]
        );

        $this->time_to_live = Validator::validateInt($time_to_live, 0, null, [
            "nullable" => true
        ]);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
