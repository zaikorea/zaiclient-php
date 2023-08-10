<?php
namespace ZaiClient\Requests\Items;

use InvalidArgumentException;
use ZaiClient\Requests\Items\ItemRequest;
use ZaiClient\Utils\Validator;

class DeleteItem extends ItemRequest
{
    private $item_ids;

    public function __construct($ids)
    {
        if (!is_array($ids)) {
            $this->item_ids = [$ids];
        } else {
            $this->item_ids = $ids;
        }

        for ($i = 0; $i < count($this->item_ids); $i++) {
            Validator::validateString($this->item_ids[$i], 1, 500, [
                "nullable" => false,
                "var_name" => "\$id",
            ]);
        }

        parent::__construct(
            "DELETE",
            null
        );
    }

    public function getQueryParams()
    {
        return [
            "id" => $this->item_ids
        ];
    }


}
