<?php
namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddProductDetailViewEvent;
use ZaiClient\Requests\Events\AddPageViewEvent;
use ZaiClient\Requests\Events\AddLikeEvent;
use ZaiClient\Requests\Events\AddCartaddEvent;
use ZaiClient\Requests\Events\AddPurchaseEvent;
use ZaiClient\Requests\Events\AddRateEvent;
use ZaiClient\Requests\Events\AddSearchEvent;
use ZaiClient\Requests\Events\AddCustomEvent;

use ZaiClient\ZaiClient;

// BackLog: Find a way to test this using a local collector-api
class EventsSendRequestTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';

    const SUCCESS_MSG = 'The given event was added successfully.';


    /* --------------------- Test ProductDetailView Event --------------------  */

    public function testProductDetailViewEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddProductDetailViewEvent('user_1', 'item_1', [
            'from' => "test_from",
            'is_zai_rec' => true,
        ]);

        $response = $client->sendRequest($request, ['is_test' => true]);

        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test PageView Event --------------------  */
    public function testPageViewEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddPageViewEvent('user_1', 'test_page_vew', [
            'from' => "test_from",
            'is_zai_rec' => true,
        ]);

        $response = $client->sendRequest($request, ['is_test' => true]);

        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test Like Event --------------------  */
    public function testLikeEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddLikeEvent('user_1', 'item_1', [
            'from' => "test_from",
            'is_zai_rec' => true,
        ]);

        $response = $client->sendRequest($request, ['is_test' => true]);

        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test Cartadd Event --------------------  */
    public function testCartaddEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddCartaddEvent('user_1', 'item_1', [
            'from' => "test_from",
            'is_zai_rec' => true,
        ]);

        $response = $client->sendRequest($request, ['is_test' => true]);

        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test Purchase Event --------------------  */
    public function testPurchaseEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddPurchaseEvent(
            "test_user_id",
            ["item_id" => "test_item_id", "price" => 10000, "quantity" => 1],
            ["is_test" => true]
        );

        $response = $client->sendRequest($request, ['is_test' => true]);

        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test Rate Event --------------------  */
    public function testRateEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddRateEvent('user_1','item_1', 3, [
            'from' => "test_from",
            'is_zai_rec' => true,
        ]);
        $response = $client->sendRequest($request, ['is_test' => true]);
        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test Search Event --------------------  */
    public function testSearchEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddSearchEvent('user_1', 'test_query', [
                'from' => "test_from",
                'is_zai_rec' => true,
        ]);
        $response = $client->sendRequest($request, ['is_test' => true]);
        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }

    /* --------------------- Test Custom Event --------------------  */
    public function testCustomEvent()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddCustomEvent(
            'user_1',
            'test_custom_event_type',
            ['item_id' => 'test_item_id', 'value' => 'test_value'],
            [
                'from' => "test_from",
                'is_zai_rec' => true,
            ]
        );
        $response = $client->sendRequest($request, ['is_test' => true]);
        $this->assertEquals(self::SUCCESS_MSG, $response->getMessage());
    }
}
