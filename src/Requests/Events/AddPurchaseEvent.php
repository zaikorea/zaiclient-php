<?php
namespace ZaiClient\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;
use ZaiClient\Utils\Util;

class AddPurchaseEvent extends EventRequest
{
    const DEFAULT_EVENT_TYPE = "purchase";

    /**
     * @param string $user_id
     * @param array $orders
     * @param array $request_options
     */
    public function __construct(
        $user_id,
        $orders,
        $request_options = []
    ) {
        if (!$orders) {
            throw new InvalidArgumentException("orders is required");
        }

        if (!is_array($orders)) {
            throw new InvalidArgumentException("orders must be an array");
        }

        // change to 2D array if $orders is 1D array (order on single item)
        // [item_id => "P001", price =>"3000", quantity =>3]  to [[item_id => "P001", price =>"3000", quantity =>3]]
        if (gettype(reset($orders)) != "array") {
            $orders = array($orders);
        }

        // Validate if $order is sequential array
        if (!Util::isSequentialArray($orders)) {
            throw new InvalidArgumentException("orders must be a sequential array");
        }

        $flattenedOrders = $this->flattenOrders($orders);

        parent::__construct(
            $user_id,
            array_map(function ($order) {
                return $order["item_id"];
            }, $flattenedOrders),
            (array_key_exists("timestamp", $request_options)
                ? $request_options["timestamp"]
                : null),
            self::DEFAULT_EVENT_TYPE,
            array_map(function ($order) {
                return $order["price"];
            }, $flattenedOrders),
            array_fill(0, count($flattenedOrders), null),
            array_map(function ($order) {
                return $order["is_zai_rec"];
            }, $flattenedOrders)
        );
    }

    private function flattenOrders($orders)
    {
        $flattenedOrders = [];

        foreach ($orders as $order) {
            for ($i = 0; $i < $order["quantity"]; $i++) {
                $flattenedOrders[] = [
                    "item_id" => $order["item_id"],
                    "price" => $order["price"],
                    "from" => (array_key_exists("from", $order)
                        ? $order["from"]
                        : null),
                    "is_zai_rec" => (array_key_exists("is_zai_rec", $order)
                        ? $order["is_zai_rec"]
                        : false),
                ];
            }
        }

        return $flattenedOrders;
    }
}
