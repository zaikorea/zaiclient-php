<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Events\Event;
use PHPUnit\Framework\TestCase;
use ZaiClient\Tests\TestUtils;

class EventTest extends TestCase
{
    /* ----------------------- Happy Path ----------------------- */
    public function testClassConstructor()
    {
        $user_id = TestUtils::generateRandomString(10);
        $item_id = TestUtils::generateRandomString(10);
        $timestamp = microtime(true);
        $event_type = TestUtils::generateRandomString(10);
        $event_value = TestUtils::generateRandomString(10);
        $from = TestUtils::generateRandomString(10);
        $is_zai_recomendation = true;
        $ttl = 60 * 60 * 24;

        $event = new Event(
            $user_id,
            $item_id,
            $timestamp,
            $event_type,
            $event_value,
            $from,
            $is_zai_recomendation,
            $ttl
        );

        $this->assertEquals(
            [
                "user_id" => $user_id,
                "item_id" => $item_id,
                "timestamp" => $timestamp,
                "event_type" => $event_type,
                "event_value" => $event_value,
                "from" => $from,
                "is_zai_recommendation" => $is_zai_recomendation,
                "time_to_live" => $ttl,
            ],
            $event->jsonSerialize()
        );
    }

    /* ----------------------- Unhappy Path ----------------------- */

    // TODO: Anyone who would like to contribute, please add more test cases here
}
