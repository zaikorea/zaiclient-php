<?php
namespace ZaiClient\Requests\Items;

use ZaiClient\Requests\Items\ItemRequest;

class DeleteItem extends ItemRequest
{
    public function __construct($id)
    {
        parent::__construct(
            "DELETE",
            $id
        );
    }
}
