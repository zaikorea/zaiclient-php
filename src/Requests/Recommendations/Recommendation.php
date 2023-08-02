<?php
namespace ZaiClient\Requests\Recommendations;

use JsonSerializable;
use ZaiClient\Utils\Validator;

class Recommendation implements JsonSerializable
{
    /**
     * @var string|null $user_id ID of the user
     */
    private $user_id;

    /**
     * @var string|null $item_id ID of the item
     */
    private $item_id;

    /**
     * @var array[string]|null $item_name Name of the item
     */
    private $item_ids;

    /**
     * @var string $recommendation_type Type of the recommendation
     */
    private $recommendation_type;

    /**
     * @var int $limit Limit of the recommendations
     */
    private $limit;

    /**
     * @var int $offset Offset of the recommendations
     */
    private $offset;

    /**
     * @var array $options Additional options
     */
    private $options;

    /**
     * @param string|null $user_id ID of the user
     * @param string|null $item_id ID of the item
     * @param array[string]|null $item_ids Name of the item
     * @param string $recommendation_type Type of the recommendation
     * @param int $limit Limit of the recommendations
     * @param int $offset Offset of the recommendations
     * @param array $options Additional options (associative array)
     */
    public function __construct(
        $user_id,
        $item_id,
        $item_ids,
        $recommendation_type,
        $limit,
        $offset,
        $recommendation_options
    ) {
        $this->user_id = Validator::validateString($user_id, 1, 500, [
            "nullable" => true,
            "var_name" => "\$user_id"
        ]);
        $this->item_id = Validator::validateString($item_id, 1, 500, [
            "nullable" => true,
            "var_name" => "\$item_id"
        ]);
        $this->item_ids = Validator::validateStringArrays(
            $item_ids,
            0,
            10000,
            ["min" => 1, "max" => 500],
            ["nullable" => true, "var_name" => "\$item_ids"]
        );
        $this->recommendation_type = Validator::validateString($recommendation_type, 1, 500);
        $this->limit = Validator::validateInt($limit, 0, 10000, [
            "var_name" => "\$limit",
        ]);
        $this->offset = Validator::validateInt($offset, 0, 10000, [
            "nullable" => true,
            "var_name" => "\$offset",
        ]);
        $this->options = $this->encodeOptions($recommendation_options);
    }

    private function encodeOptions($options)
    {
        Validator::validateJsonSerializable($options, 1000, [
            "nullable" => true,
            "var_name" => "\$options",
        ]);

        // Error not raised
        if (is_null($options)) {
            return null;
        }

        return json_encode($options);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
