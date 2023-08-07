<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;

class AddCartaddEvent extends EventRequest
{
    const DEFAULT_EVENT_TYPE = "cartadd";
    const DEFAULT_EVENT_VALUE = "null";

    /**
     * @param string $user_id
     * @param string $item_id
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $item_id,
        $request_options = []
    ) {
        $this->validate($item_id);

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
            (array_key_exists("is_zai_rec", $request_options)
                ? [$request_options["is_zai_rec"]]
                : [false])
        );
    }

    private function validate($item_id)
    {
        if (!$item_id) {
            throw new InvalidArgumentException("item_id is required");
        }

        if (!is_string($item_id)) {
            throw new InvalidArgumentException("item_id must be a string");
        }
    }
}
