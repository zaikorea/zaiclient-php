<?php

/**
 * EventLogTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\PurchaseEvent;
use ZaiKorea\ZaiClient\Requests\ProductDetailViewEvent;
use ZaiKorea\ZaiClient\Requests\PageViewEvent;
use ZaiKorea\ZaiClient\Requests\SearchEvent;
use ZaiKorea\ZaiClient\Requests\LikeEvent;
use ZaiKorea\ZaiClient\Requests\CartaddEvent;
use ZaiKorea\ZaiClient\Requests\RateEvent;
use ZaiKorea\ZaiClient\Requests\CustomEvent;
use ZaiKorea\ZaiClient\Exceptions\ZaiClientException;
use ZaiKorea\ZaiClient\Exceptions\BatchSizeLimitExceededException;
use ZaiKorea\ZaiClient\Configs\Config;


require_once 'TestUtils.php';

class EventLogTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';

    private $add_event_msg = 'The given event was added successfully.';
    private $update_event_msg = 'The given event was updated successfully.';
    private $delete_event_msg = 'The given event was removed successfully.';

    /* --------------------- Test Purchase Event --------------------  */
    // Tests addEventLog, updateEventLog, deleteEventLog

    public function testAddSinglePurchaseEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-purchase';
        $order = array('item_id' => 'P1000009', 'price' => 11000, 'count' => 1);

        $purchase_event = new PurchaseEvent($customer_id, $order);
        $response = $client->addEventLog($purchase_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultiplePurchaseEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-purchase';
        $order = array(
            ['item_id' => 'P1234567', 'price' => 11000, 'count' => 2],
            ['item_id' => 'P5678901', 'price' => 11000, 'count' => 3]
        );

        $options = array(
            'timestamp' => time()
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response = $client->addEventLog($purchase_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testUpdatePurchaseEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-purchase';
        $order = array(
            ['item_id' => 'P1000009', 'price' => 14000, 'count' => 1],
        );
        $options = array(
            'timestamp' => time()
        );
        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response_status = $client->addEventLog($purchase_event, $options);

        $order = array(
            ['item_id' => 'P1000009', 'price' => 24000, 'count' => 1],
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, ['timestamp' => $purchase_event->getTimestamp()]);
        $response = $client->updateEventLog($purchase_event);

        self::assertSame($this->update_event_msg, $response->getMessage());
    }

    public function testDeletePurchaseEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-purchase';
        $order = array(
            ['item_id' => 'P1000007', 'price' => 900, 'count' => 2],
            ['item_id' => 'P1000008', 'price' => 900, 'count' => 3]
        );
        $options = array(
            'timestamp' => time()
        );
        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $client->addEventLog($purchase_event);

        $purchase_event = new PurchaseEvent($customer_id, $order, ['timestamp' => $purchase_event->getTimestamp()]);
        $response = $client->deleteEventLog($purchase_event);

        self::assertSame($this->delete_event_msg, $response->getMessage());
    }

    /* ----------------------- Test ProductDetailView Event -----------------------  */
    // From here on only test the addEventLog

    public function testAddSingleProductDetailViewEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-productdetailview';
        $item_id = 'P1000005';

        $product_detail_view_event = new ProductDetailViewEvent($customer_id, $item_id);
        $response = $client->addEventLog($product_detail_view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleProductDetailViewEventLog()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_STR_ARG_ERRMSG, ProductDetailViewEvent::class, '__construct', 2)    
        );
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-productdetailview';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $product_detail_view_event = new ProductDetailViewEvent($customer_id, $item_ids, $options);
    }

    /* ----------------------- Test PageView Event -----------------------  */
    // From here on only test the addEventLog

    public function testAddPageViewEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-pageview';
        $event_value = 'homepage';

        $page_view_event = new PageViewEvent($customer_id, $event_value);
        $response = $client->addEventLog($page_view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddPageViewEventLogWithEmptyEventValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_STR_ARG_ERRMSG, PageViewEvent::class, '__construct', 2)
        );
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-search';
        $event_value = null;

        $search_event = new PageViewEvent($customer_id, $event_value);
        $response = $client->addEventLog($search_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    /* ----------------------- Test Search Event -----------------------  */
    // From here on only test the addEventLog

    public function testAddSearchEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-search';
        $event_value = 'Blue Jeans';

        $search_event = new SearchEvent($customer_id, $event_value);
        $response = $client->addEventLog($search_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddSearchEventLogWithEmptyQuery()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage((
            sprintf(Config::EMPTY_STR_ARG_ERRMSG, SearchEvent::class, '__construct', 2)
        ));
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-search';
        $event_value = null;

        $search_event = new SearchEvent($customer_id, $event_value);
        $response = $client->addEventLog($search_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }


    /* ----------------------- Test Like Event -----------------------  */
    // Only test the addEventLog

    public function testAddSingleLikeEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-like';
        $item_id = 'P1000005';

        $like_event = new LikeEvent($customer_id, $item_id);
        $response = $client->addEventLog($like_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleLikeEventLog()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_STR_ARG_ERRMSG, LikeEvent::class, '__construct', 2)
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-likes';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $like_event = new LikeEvent($customer_id, $item_ids, $options);
        $response = $client->addEventLog($like_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    /* ------------------- Test Cartdd Event ---------------------  */
    // Only test the addEventLog
    // TODO: test delete

    public function testAddSingleCartaddEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-cartadd';
        $item_id = 'P1000005';

        $cartadd_event = new CartaddEvent($customer_id, $item_id);
        $response = $client->addEventLog($cartadd_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleCartaddEventLog()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_STR_ARG_ERRMSG, CartaddEvent::class, '__construct', 2)
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-cartadds';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $cartadd_event = new CartaddEvent($customer_id, $item_ids, $options);
        $response = $client->addEventLog($cartadd_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }


    /* ------------------- Test Rate Event ---------------------  */
    // Only test the addEventLog

    public function testAddSingleRateEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-rate';
        $item_id = ['item_id' => 'P1000005', 'value' => 3.0];

        $rate_event = new RateEvent($customer_id, $item_id);
        $response = $client->addEventLog($rate_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleRateEventLog()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Rate event doesn\'t support batch'
        );
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-rates';
        $rate_actions = [
            ['item_id' => 'P1000005', 'value' => 3.0],
            ['item_id' => 'P1000006', 'value' => 4.0]
        ];

        $options = array(
            'timestamp' => time()
        );

        $rate_event = new RateEvent($customer_id, $rate_actions, $options);
        $response = $client->addEventLog($rate_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testBadRateActionTypeOnRateEventWithoutValueKeyword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Rate event doesn\'t support batch'
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = [
            ['item_id' => 'P1000007'],
        ];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithAssociativeArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Rate event doesn\'t support batch'
        );


        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = [
            'P20007' => ['price' => 11000, 'count' => 3],
            'P20008' => ['price' => 12000, 'count' => 2],
        ];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithEmptyArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_ARR_ERRMSG, RateEvent::class, '__construct', 2)
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = [];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_ARR_ERRMSG, RateEvent::class, '__construct', 2)
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = 'P1234678';

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithMissingKeyword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::ARR_FORM_ERRMSG, RateEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'value' => 5.0] ] (1D array available if recording single rate action)")
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = ['item_id' => 'P1112345'];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithWrongKeyword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::ARR_FORM_ERRMSG, RateEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'value' => 5.0] ] (1D array available if recording single rate action)")
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = ['item_id' => 'P1112345', 'price' => 3000];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    /* ------------------- Test Custom Event ---------------------  */
    // Only test the addEventLog

    public function testAddSinglecustomEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-single-custom';
        $custom_event = 'search';
        $custom_action = ['item_id' => 'P1000005', 'value' => null];

        $custom_event = new CustomEvent($customer_id, $custom_event, $custom_action);
        $response = $client->addEventLog($custom_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultiplecustomEventLog()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-customs';
        $custom_event_type = 'search';

        $custom_action = array(
            ['item_id' => 'P1000009', 'value' => null],
            ['item_id' => 'P1000010', 'value' => null]
        );
        $options = array(
            'timestamp' => time()
        );

        $custom_event = new CustomEvent($customer_id, $custom_event_type, $custom_action, $options);
        $response = $client->addEventLog($custom_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddCustomEventLogWithIndexedArray()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-add-multi-customs';
        $custom_event_type = 'search';

        $custom_action = array(
            ['item_id' => 'P1000009', 'value' => null],
            ['item_id' => 'P1000010', 'value' => null],
            ['item_id' => 'P1000011', 'value' => null],
            ['item_id' => 'P1000012', 'value' => null],
        );
        $options = array(
            'timestamp' => time()
        );

        $custom_event = new CustomEvent($customer_id, $custom_event_type, $custom_action, $options);
        $response = $client->addEventLog($custom_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    /* ------------------- Test Errors ---------------------  */

    /**
     * @expectException ZaiClientException
     */
    public function testBadSecret()
    {
        $this->expectException(ZaiClientException::class);

        $bad_secret = '123456777';
        $client = new ZaiClient(self::CLIENT_ID, $bad_secret);
        $customer_id = 'php-raise-error';
        $item_id = 'P1000005';

        $custom_event = new ProductDetailViewEvent($customer_id, $item_id);
        $client->addEventLog($custom_event); // This should throw ZaiClientException
    }

    /**
     * @expectException InvalidArgumentException
     */
    public function testUpdateMultipleEvent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $item_ids = ['P1000005', 'P1000006', 'P100007'];

        $view_event = new ProductDetailViewEvent($customer_id, $item_ids);
        $client->updateEventLog($view_event); // This should throw ZaiClientException
    }

    public function testBadOrdersTypeOnPurchaseEventWithoutCountKeyword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectException(\InvalidArgumentException::class);

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $orders = [
            ['item_id' => 'P1000007', 'price' => 11000],
        ];

        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }

    public function testBadOrdersTypeOnPurchaseEventWithAssociativeArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::ARR_FORM_ERRMSG, PurchaseEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'price' => 50000, 'count' => 3] ] (1D array available if recording single order)")
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $orders = [
            ['P20007' => ['price' => 11000, 'count' => 3]],
            ['P20008' => ['price' => 12000, 'count' => 2]],
        ];

        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }

    public function testBadOrdersTypeOnPurchaseEventWithEmptyArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_ARR_ERRMSG, PurchaseEvent::class, '__construct', 2)
        );

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';
        $orders = [];

        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }

    public function testBadCustomerIdOnProductDetailViewEvent()
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = generateRandomString(101);

        $item_id = ['P12345'];
        $view_event = new ProductDetailViewEvent($customer_id, $item_id);
        $client->addEventLog($view_event);
    }

    public function testBadItemIdOnProductDetailViewEvent()
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';

        $item_id = [generateRandomString(101)];
        $view_event = new ProductDetailViewEvent($customer_id, $item_id);
        $client->addEventLog($view_event);
    }

    public function testBadEventTypeCustomerEvent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Length of event type must be between 1 and 100.');

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';

        $custom_event_type = generateRandomString(102);
        $custom_action = ['item_id' => 'P99999', 'value' => 9];
        $custom_event = new CustomEvent($customer_id, $custom_event_type, $custom_action);
        $client->addEventLog($custom_event);
    }

    public function testBatchLimiteExceededException()
    {
        $this->expectException(BatchSizeLimitExceededException::class);
        $this->expectExceptionMessage(sprintf("Number of total records cannot exceed 50, but your Event holds %d.", 55));

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $customer_id = 'php-raise-error';

        $orders = array(
            ['item_id' => 'P1234567', 'price' => 11000, 'count' => 52],
            ['item_id' => 'P5678901', 'price' => 11000, 'count' => 3]
        );
        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }
}
