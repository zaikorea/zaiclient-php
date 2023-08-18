<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;

class AddSearchEvent extends EventRequest
{
    const DEFAULT_EVENT_TYPE = "search";
    const DEFAULT_ITEM_ID = "null";

    /**
     * @param string $user_id
     * @param string $search_query
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $search_query,
        $request_options = []
    ) {
        $this->validate($search_query);

        parent::__construct(
            $user_id,
            [self::DEFAULT_ITEM_ID],
            (array_key_exists("timestamp", $request_options)
                ? $request_options["timestamp"]
                : null),
            self::DEFAULT_EVENT_TYPE,
            [$search_query],
            (array_key_exists("from", $request_options)
                ? [$request_options["from"]]
                : [null]),
            (array_key_exists("is_zai_rec", $request_options)
                ? [$request_options["is_zai_rec"]]
                : [false])
        );
    }

    private function validate($search_query)
    {
        if (!is_string($search_query)) {
            throw new InvalidArgumentException("search_query must be string");
        }
    }
}
