<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;

class AddRateEvent extends EventRequest
{
    const DEFAULT_EVENT_TYPE = "rate";

    /**
     * @param string $user_id
     * @param string $item_id
     * @param float|int $rating
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $item_id,
        $rating,
        $request_options = []
    ) {
        $this->validate($item_id, $rating);

        parent::__construct(
            $user_id,
            [$item_id],
            (array_key_exists("timestamp", $request_options)
                ? $request_options["timestamp"]
                : null),
            self::DEFAULT_EVENT_TYPE,
            [(string) $rating],
            (array_key_exists("from", $request_options)
                ? [$request_options["from"]]
                : [null]),
            (array_key_exists("is_zai_rec", $request_options)
                ? [$request_options["is_zai_rec"]]
                : [false])
        );
    }

    private function validate($item_id, $rating)
    {
        if (!$item_id) {
            throw new InvalidArgumentException("item_id is required");
        }

        if (is_array($item_id)) {
            throw new InvalidArgumentException("item_id must be a string, not an array");
        }

        if (is_null($rating)) {
            throw new InvalidArgumentException("rating is required");
        }

        if (is_array($rating)) {
            throw new InvalidArgumentException("rating must be a number, not an array");
        }

        if (!is_int($rating) && !is_float($rating)) {
            throw new InvalidArgumentException("rating must be a float|int");
        }
    }
}
