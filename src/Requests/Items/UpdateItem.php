<?php
namespace ZaiClient\Requests\Items;

use InvalidArgumentException;
use ZaiClient\Requests\Items\ItemRequest;

class UpdateItem extends ItemRequest
{
    public function __construct($items)
    {
        if (!is_array($items)) {
            $items = [$items];
        }

        for ($i = 0; $i < count($items); $i++) {
            if (!(is_a($items[$i], Item::class))) {
                throw new InvalidArgumentException(
                    "Element $i must be an instance of Item"
                );
            }
        }

        parent::__construct("PUT", $items);
    }
}
