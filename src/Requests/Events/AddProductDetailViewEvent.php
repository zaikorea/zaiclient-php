<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;

class AddProductDetailViewEvent extends EventRequest
{
    const DEFAULT_EVENT_TYPE = "product_detail_view";
    const DEFAULT_EVENT_VALUE = "null";

    public function __construct(
        $user_id,
        $item_id,
        $request_options = []
    ) {
        if (!$item_id) {
            throw new InvalidArgumentException("item_id is required");
        }

        if (is_array($item_id)) {
            throw new InvalidArgumentException("item_id must be a string, not an array");
        }

        parent::__construct(
            $user_id,
            [$item_id],
            (array_key_exists("timestamp", $request_options)
                ? $request_options["timestamp"]
                : null),
            self::DEFAULT_EVENT_TYPE,
            [self::DEFAULT_EVENT_VALUE],
            (array_key_exists("from", $request_options)
                ? [$request_options["from"]]
                : [null]),
            (array_key_exists("is_zai_recommendation", $request_options)
                ? [$request_options["is_zai_recommendation"]]
                : [false])
        );
    }
}
