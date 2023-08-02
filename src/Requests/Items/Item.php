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
    public $item_name;
    public $category_id_1;
    public $category_name_1;
    public $category_id_2;
    public $category_name_2;
    public $category_id_3;
    public $category_name_3;
    public $brand_id;
    public $brand_name;
    public $description;
    public $created_timestamp;
    public $updated_timestamp;
    public $is_active;
    public $is_soldout;
    public $promote_on;
    public $item_group;
    public $rating;
    public $price;
    public $click_counts;
    public $purchase_counts;
    public $image_url;
    public $item_url;
    public $miscellaneous;

    public function __construct($data)
    {
        // Validate and assign values
        $this->item_id = Validator::validateString($data['item_id'], 1, 500);
        $this->item_name = array_key_exists('item_name', $data) ? Validator::validateString($data['item_name'], 1, 2000, true) : null;
        $this->category_id_1 = array_key_exists('category_id_1', $data) ? Validator::validateString($data['category_id_1'], 0, 2000, true) : null;
        $this->category_name_1 = array_key_exists('category_name_1', $data) ? Validator::validateString($data['category_name_1'], 0, 2000, true) : null;
        $this->category_id_2 = array_key_exists('category_id_2', $data) ? Validator::validateString($data['category_id_2'], 0, 2000, true) : null;
        $this->category_name_2 = array_key_exists('category_name_2', $data) ? Validator::validateString($data['category_name_2'], 0, 2000, true) : null;
        $this->category_id_3 = array_key_exists('category_id_3', $data) ? Validator::validateString($data['category_id_3'], 0, 2000, true) : null;
        $this->category_name_3 = array_key_exists('category_name_3', $data) ? Validator::validateString($data['category_name_3'], 0, 2000, true) : null;
        $this->brand_id = array_key_exists('brand_id', $data) ? Validator::validateString($data['brand_id'], 0, 2000, true) : null;
        $this->brand_name = array_key_exists('brand_name', $data) ? Validator::validateString($data['brand_name'], 0, 2000, true) : null;
        $this->description = array_key_exists('description', $data) ? Validator::validateString($data['description'], 0, 65535, true) : null;
        $this->created_timestamp = array_key_exists('created_timestamp', $data) ? Validator::validateTimestamp($data['created_timestamp'], true) : null;
        $this->updated_timestamp = array_key_exists('updated_timestamp', $data) ? Validator::validateTimestamp($data['updated_timestamp'], true) : null;
        $this->is_active = array_key_exists('is_active', $data) ? Validator::validateBoolean($data['is_active'], true) : null;
        $this->is_soldout = array_key_exists('is_soldout', $data) ? Validator::validateBoolean($data['is_soldout'], true) : null;
        $this->promote_on = array_key_exists('promote_on', $data) ? Validator::validateString($data['promote_on'], 0, 2000, true) : null;
        $this->item_group = array_key_exists('item_group', $data) ? Validator::validateString($data['item_group'], 0, 2000, true) : null;
        $this->rating = array_key_exists('rating', $data) ? Validator::validateFloat($data['rating'], 0, null, true) : null;
        $this->price = array_key_exists('price', $data) ? Validator::validateFloat($data['price'], 0, null, true) : null;
        $this->click_counts = array_key_exists('click_counts', $data) ? Validator::validateInt($data['click_counts'], 0, null, true) : null;
        $this->purchase_counts = array_key_exists('purchase_counts', $data) ? Validator::validateInt($data['purchase_counts'], 0, null, true) : null;
        $this->image_url = array_key_exists('image_url', $data) ? Validator::validateString($data['image_url'], 0, 2000, true) : null;
        $this->item_url = array_key_exists('item_url', $data) ? Validator::validateString($data['item_url'], 0, 2000, true) : null;
        $this->miscellaneous = array_key_exists('miscellaneous', $data) ? Validator::validateString($data['miscellaneous'], 0, 65535, true) : null;
    }



    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
