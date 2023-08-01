<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Events\EventRequest;

class EventRequestTest extends TestCase
{
    /* ----------------------- Happy Path ----------------------- */
    public function testClassConstructorWithSingleEvent()
    {
        $user_id = "User_Id_1";
        $item_ids = ["Item_Id_1"];
        $timestamp = microtime(true);
        $event_type = "test_event_type";
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        $expected = json_encode(
            [
                "user_id" => $user_id,
                "item_id" => $item_ids[0],
                "timestamp" => $timestamp,
                "event_type" => $event_type,
                "event_value" => $event_values[0],
                "from" => $from_values[0],
                "is_zai_recommendation" => $is_zai_recommendation[0],
                "time_to_live" => null,
            ]
        );

        $request = new EventRequest($user_id, $item_ids, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);

        $actual = json_encode(
            $request->getPayload()
        );

        $this->assertJsonStringEqualsJsonString(
            $expected,
            $actual
        );
    }

    public function testClassConstructorWithMultipleEvent()
    {
        $user_id = "User_Id_1";
        $item_ids = ["Item_Id_1", "Item_Id_2"];
        $timestamp = microtime(true);
        $event_type = "test_event_type";
        $event_values = ["test_event_value_1", "test_event_value_2"];
        $from_values = ["test_from_value_1", "test_from_value_2"];
        $is_zai_recommendation = [true, false];

        $expected = json_encode([


            [
                "user_id" => $user_id,
                "item_id" => $item_ids[0],
                "timestamp" => $timestamp,
                "event_type" => $event_type,
                "event_value" => $event_values[0],
                "from" => $from_values[0],
                "is_zai_recommendation" => $is_zai_recommendation[0],
                "time_to_live" => null,
            ],
            [
                "user_id" => $user_id,
                "item_id" => $item_ids[1],
                "timestamp" => $timestamp + Config::EPSILON,
                "event_type" => $event_type,
                "event_value" => $event_values[1],
                "from" => $from_values[1],
                "is_zai_recommendation" => $is_zai_recommendation[1],
                "time_to_live" => null,
            ]
        ]);

        $request = new EventRequest($user_id, $item_ids, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);

        $actual = json_encode(
            $request->getPayload()
        );

        $this->assertJsonStringEqualsJsonString(
            $expected,
            $actual
        );
    }

    /* ----------------------- Unhappy Path --------------------- */
    public function testClassConstructorWhereItemIdsAreNotList()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = "Item_Id_1";
        $timestamp = microtime(true);
        $event_type = "test_event_type";
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereTimestampIsNotFloat()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = (int) microtime(true);
        $event_type = "test_event_type";
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereTimestampIsList()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = [microtime(true)];
        $event_type = "test_event_type";
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereEventTypeIsNotString()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = microtime(true);
        $event_type = 123;
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereEventTypeIsList()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = microtime(true);
        $event_type = ["test_event_type"];
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereEventValuesIsNotList()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = microtime(true);
        $event_type = "event";
        $event_values = "not a list";
        $from_values = ["test_from_value"];
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereFromValuesIsNotList()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = microtime(true);
        $event_type = "event";
        $event_values = ["value"];
        $from_values = "not a list";
        $is_zai_recommendation = [true];

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }

    public function testClassConstructorWhereIsZaiRecommendationNotList()
    {
        $this->expectException(InvalidArgumentException::class);
        $user_id = "User_Id_1";
        $item_id = ["Item_Id_1"];
        $timestamp = microtime(true);
        $event_type = "test_event_type";
        $event_values = ["test_event_value"];
        $from_values = ["test_from_value"];
        $is_zai_recommendation = true;

        new EventRequest($user_id, $item_id, $timestamp, $event_type, $event_values, $from_values, $is_zai_recommendation);
    }
}
