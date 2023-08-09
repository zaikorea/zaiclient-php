<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;

class AddPageViewEvent extends EventRequest
{
    const DEFAULT_EVENT_TYPE = "page_view";
    const DEFAULT_ITEM_ID = "null";

    /**
     * @param string $user_id
     * @param string $page_type
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $page_type,
        $request_options = []
    ) {
        $this->validate($page_type);

        parent::__construct(
            $user_id,
            [self::DEFAULT_ITEM_ID],
            (array_key_exists("timestamp", $request_options)
                ? $request_options["timestamp"]
                : null),
            self::DEFAULT_EVENT_TYPE,
            [$page_type],
            (array_key_exists("from", $request_options)
                ? [$request_options["from"]]
                : [null]),
            (array_key_exists("is_zai_rec", $request_options)
                ? [$request_options["is_zai_rec"]]
                : [false])
        );
    }

    private function validate($page_type)
    {
        if (!$page_type) {
            throw new InvalidArgumentException("page_type is required");
        }

        if (!is_string($page_type)) {
            throw new InvalidArgumentException("page_type must be a string");
        }
    }
}
