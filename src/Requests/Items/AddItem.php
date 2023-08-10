<?php
namespace ZaiClient\Requests\Items;

use InvalidArgumentException;

use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Items\ItemRequest;

class AddItem extends ItemRequest
{

    /**
     * Create a new AddItem request
     * @param \ZaiClient\Requests\Items\Item|array[\ZaiClient\Requests\Items\Item] $items
     */
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

            if (!isset($items[$i]->item_name)) {
                throw new InvalidArgumentException(
                    "Item name must be set when adding an item"
                );
            }
        }

        parent::__construct("POST", $items);
    }
}
