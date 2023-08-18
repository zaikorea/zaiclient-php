<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;
use ZaiClient\Utils\Util;

class AddCustomEvent extends EventRequest
{
    /**
     * @param string $user_id
     * @param string $custom_event_type
     * @param array $custom_actions
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $custom_event_type,
        $custom_actions,
        $request_options = []
    ) {
        if (!$custom_actions) {
            throw new InvalidArgumentException("custom_actions is required");
        }

        if (!is_array($custom_actions)) {
            throw new InvalidArgumentException("custom_actions must be an array");
        }

        // change to 2D array if $custom_actions is 1D array (custom_actions on single item)
        // [item_id => "P001", value =>"watch"]  to [[item_id => "P001", value =>"watch"]]
        if (gettype(reset($custom_actions)) != "array") {
            $custom_actions = array($custom_actions);
        }

        // Validate if $custom_actions is sequential array
        if (!Util::isSequentialArray($custom_actions)) {
            throw new InvalidArgumentException("custom_actions must be a sequential array");
        }

        $flattenedCustomActions = $this->flattenCustomActions($custom_actions);

        parent::__construct(
            $user_id,
            array_map(function ($custom_action) {
                return $custom_action["item_id"];
            }, $flattenedCustomActions),
            (array_key_exists("timestamp", $request_options)
                ? $request_options["timestamp"]
                : null),
            $custom_event_type,
            array_map(function ($custom_action) {
                return $custom_action["value"];
            }, $flattenedCustomActions),
            array_fill(0, count($flattenedCustomActions), null),
            array_map(function ($custom_action) {
                return $custom_action["is_zai_rec"];
            }, $flattenedCustomActions)
        );
    }

    /**
     * @param array $custom_actions
     * @return array
     */
    private function flattenCustomActions($custom_actions)
    {
        $flattenOrders = [];

        foreach ($custom_actions as $action) {
            if (!array_key_exists("item_id", $action)) {
                throw new InvalidArgumentException("item_id is required");
            }

            if (!array_key_exists("item_id", $action)) {
                throw new InvalidArgumentException("value is required");
            }

            $flattenOrders[] = [
                "item_id" => $action["item_id"],
                "value" => $action["value"],
                "is_zai_rec" => (array_key_exists("is_zai_rec", $action)
                    ? $action["is_zai_rec"]
                    : false),
                "from" => (array_key_exists("from", $action)
                    ? $action["from"]
                    : null)
            ];
        }

        return $flattenOrders;
    }
}
