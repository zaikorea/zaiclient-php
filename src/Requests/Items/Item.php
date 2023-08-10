<?php
namespace ZaiClient\Requests\Items;

use JsonSerializable;
use ZaiClient\Utils\Validator;

class Item implements JsonSerializable
{

    /**
     * @var string $item_id ID of the item
     */
    public $item_id;

    /**
     * @var string|null $item_name Name of the item
     */
    public $item_name;

    /**
     * @var string|null $category_id_1 ID of the first category
     */
    public $category_id_1;

    /**
     * @var string|null $category_name_1 Name of the first category
     */
    public $category_name_1;

    /**
     * @var string|null $category_id_2 ID of the second category
     */
    public $category_id_2;

    /**
     * @var string|null $category_name_2 Name of the second category
     */
    public $category_name_2;

    /**
     * @var string|null $category_id_3 ID of the third category
     */
    public $category_id_3;

    /**
     * @var string|null $category_name_3 Name of the third category
     */
    public $category_name_3;

    /**
     * @var string|null $category_id_4 ID of the fourth category
     */
    public $brand_id;

    /**
     * @var string|null $brand_name Name of the brand
     */
    public $brand_name;

    /**
     * @var string|null $description Description of the item
     */
    public $description;

    /**
     * @var string|null $created_timestamp Timestamp of when the item was created
     */
    public $created_timestamp;

    /**
     * @var string|null $updated_timestamp Timestamp of when the item was last updated
     */
    public $updated_timestamp;

    /**
     * @var bool $is_active Whether the item is active
     */
    public $is_active;

    /**
     * @var bool $is_soldout Whether the item is sold out
     */
    public $is_soldout;

    /**
     * @var string|null $promote_on
     */
    public $promote_on;

    /**
     * @var string|null $item_group
     */
    public $item_group;

    /**
     * @var float|null $rating Rating of the item
     */
    public $rating;

    /**
     * @var float|null $price Price of the item
     */
    public $price;

    /**
     * @var int|null $click_counts Number of clicks
     */
    public $click_counts;

    /**
     * @var int|null $purchase_counts Number of purchases
     */
    public $purchase_counts;

    /**
     * @var string|null $image_url URL of the image
     */
    public $image_url;

    /**
     * @var string|null $item_url URL of the item
     */
    public $item_url;

    /**
     * @var string|null $miscellaneous Miscellaneous information
     */
    public $miscellaneous;

    /**
     * @var string $item_id ID of the item
     * @var string $item_name Name of the item
     * @var string $properties Associative array of item properties
     */
    public function __construct($item_id, $item_name = null, $properties = [])
    {
        // Validate and assign values
        $this->item_id = Validator::validateString($item_id, 1, 500);
        $this->item_name = $item_name;
        $this->category_id_1 = (array_key_exists('category_id_1', $properties)
            ? Validator::validateString($properties['category_id_1'], 0, 2000, [
                'nullable' => true,
                'var_name' => '\$cateogory_id_1'
            ]) : null);
        $this->category_name_1 = (array_key_exists('category_name_1', $properties)
            ? Validator::validateString($properties['category_name_1'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$category_name_1"
            ]) : null);
        $this->category_id_2 = (array_key_exists('category_id_2', $properties)
            ? Validator::validateString($properties['category_id_2'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$category_id_2"
            ]) : null);
        $this->category_name_2 = (array_key_exists('category_name_2', $properties)
            ? Validator::validateString($properties['category_name_2'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$category_name_2"
            ]) : null);
        $this->category_id_3 = (array_key_exists('category_id_3', $properties)
            ? Validator::validateString($properties['category_id_3'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$category_id_3"
            ]) : null);
        $this->category_name_3 = (array_key_exists('category_name_3', $properties)
            ? Validator::validateString($properties['category_name_3'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$category_name_3"
            ]) : null);
        $this->brand_id = (array_key_exists('brand_id', $properties)
            ? Validator::validateString($properties['brand_id'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$brand_id"
            ]) : null);
        $this->brand_name = (array_key_exists('brand_name', $properties)
            ? Validator::validateString($properties['brand_name'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$brand_name"
            ]) : null);
        $this->description = (array_key_exists('description', $properties)
            ? Validator::validateString($properties['description'], 0, 65535, [
                "nullable" => true,
                "var_name" => "\$description"
            ]) : null);
        $this->created_timestamp = (array_key_exists('created_timestamp', $properties)
            ? Validator::validateTimestamp($properties['created_timestamp'], [
                "nullable" => null,
                "var_name" => "\$created_timestamp"
            ]) : null);
        $this->updated_timestamp = (array_key_exists('updated_timestamp', $properties)
            ? Validator::validateTimestamp($properties['updated_timestamp'], [
                "nullable" => true,
                "var_name" => "\$updated_timestamp"
            ]) : null);
        $this->is_active = (array_key_exists('is_active', $properties)
            ? Validator::validateBoolean($properties['is_active'], [
                "nullable" => false,
                "var_name" => "\$is_active"
            ]) : true);
        $this->is_soldout = (array_key_exists('is_soldout', $properties)
            ? Validator::validateBoolean($properties['is_soldout'], [
                "nullable" => false,
                "var_name" => "\$is_soldout"
            ]) : false);
        $this->promote_on = (array_key_exists('promote_on', $properties)
            ? Validator::validateString($properties['promote_on'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$promote_on"
            ]) : null);
        $this->item_group = (array_key_exists('item_group', $properties)
            ? Validator::validateString($properties['item_group'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$item_group"
            ]) : null);
        $this->rating = (array_key_exists('rating', $properties)
            ? Validator::validateFloat($properties['rating'], 0, null, [
                "nullable" => true,
                "var_name" => "\$rating"
            ]) : null);
        $this->price = (array_key_exists('price', $properties)
            ? Validator::validateFloat($properties['price'], 0, null, [
                "nullable" => true,
                "var_name" => "\$price"
            ]) : null);
        $this->click_counts = (array_key_exists('click_counts', $properties)
            ? Validator::validateInt($properties['click_counts'], 0, null, [
                "nullable" => true,
                "var_name" => "\$click_counts"
            ]) : null);
        $this->purchase_counts = (array_key_exists('purchase_counts', $properties)
            ? Validator::validateInt($properties['purchase_counts'], 0, null, [
                "nullable" => true,
                "var_name" => "\$purchase_counts"
            ]) : null);
        $this->image_url = (array_key_exists('image_url', $properties)
            ? Validator::validateString($properties['image_url'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$image_url"
            ]) : null);
        $this->item_url = (array_key_exists('item_url', $properties)
            ? Validator::validateString($properties['item_url'], 0, 2000, [
                "nullable" => true,
                "var_name" => "\$item_url"
            ]) : null);
        $this->miscellaneous = (array_key_exists('miscellaneous', $properties)
            ? Validator::validateString($properties['miscellaneous'], 0, 65535, [
                "nullable" => true,
                "var_name" => "\$miscellaneous"
            ]) : null);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
