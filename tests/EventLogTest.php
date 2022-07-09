<?php
/**
 * EventLogTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\PurchaseEvent;
use ZaiKorea\ZaiClient\Requests\ViewEvent;
use ZaiKorea\ZaiClient\Requests\LikeEvent;
use ZaiKorea\ZaiClient\Requests\CartaddEvent;
use ZaiKorea\ZaiClient\Requests\RateEvent;
use ZaiKorea\ZaiClient\Requests\CustomEvent;
use ZaiKorea\ZaiClient\Exceptions\ZaiClientException;
use ZaiKorea\ZaiClient\Configs\Config;

define('SECRET', getenv('ZAI_TEST'));

class EventLogTest extends TestCase {
    private $client_id = 'test';
    // private $client_secret = getenv('ZAI_TEST');
    private $add_event_msg = 'The given event was added successfully.';
    private $update_event_msg = 'The given event was updated successfully.';
    private $delete_event_msg = 'The given event was removed successfully.';

    /* --------------------- Test Purchase Event --------------------  */
    // Tests addEventLog, updateEventLog, deleteEventLog

    public function testAddSinglePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-purchase';
        $order = array('item_id'=> 'P1000009', 'price'=> 11000, 'count'=> 1);

        $purchase_event = new PurchaseEvent($customer_id, $order);
        $response = $client->addEventLog($purchase_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultiplePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-multi-purchase';
        $order = array(
            ['item_id' => 'P1234567', 'price'=> 11000, 'count'=> 2],
            ['item_id' => 'P5678901', 'price'=> 11000, 'count'=> 3]
        );

        $options = array(
            'timestamp' => time()
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response = $client->addEventLog($purchase_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testUpdatePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-purchase';
        $order = array(
            ['item_id' => 'P1000009', 'price'=> 14000, 'count'=> 1],
        );
        $options = array(
            'timestamp' => time()
        );
        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response_status = $client->addEventLog($purchase_event, $options);

        $order = array(
            ['item_id' => 'P1000009', 'price'=> 24000, 'count'=> 1],
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, ['timestamp'=>$purchase_event->getTimestamp()]);
        $response = $client->updateEventLog($purchase_event);

        self::assertSame($this->update_event_msg, $response->getMessage());
    }

    public function testDeletePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-multi-purchase';
        $order = array(
            ['item_id' => 'P1000007', 'price'=> 900, 'count'=> 2],
            ['item_id' => 'P1000008', 'price'=> 900, 'count'=> 3]
        );
        $options = array(
            'timestamp' => time()
        );
        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $client->addEventLog($purchase_event);

        $purchase_event = new PurchaseEvent($customer_id, $order, ['timestamp'=>$purchase_event->getTimestamp()]);
        $response = $client->deleteEventLog($purchase_event);

        self::assertSame($this->delete_event_msg, $response->getMessage());
    }

    /* ----------------------- Test View Event -----------------------  */
    // From here on only test the addEventLog

    public function testAddSingleViewEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-view';
        $item_id = 'P1000005';

        $view_event = new ViewEvent($customer_id, $item_id);
        $response = $client->addEventLog($view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleViewEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-multi-views';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $view_event = new ViewEvent($customer_id, $item_ids, $options);
        $response_status = $client->addEventLog($view_event);

        $response = $client->addEventLog($view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    /* ----------------------- Test Like Event -----------------------  */
    // Only test the addEventLog

    public function testAddSingleLikeEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-like';
        $item_id = 'P1000005';

        $like_event = new LikeEvent($customer_id, $item_id);
        $response = $client->addEventLog($like_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleLikeEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-multi-likes';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $like_event = new ViewEvent($customer_id, $item_ids, $options);
        $response = $client->addEventLog($like_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    /* ------------------- Test Cartdd Event ---------------------  */
    // Only test the addEventLog
    // TODO: test delete

    public function testAddSingleCartaddEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-cartadd';
        $item_id = 'P1000005';

        $cartadd_event = new CartaddEvent($customer_id, $item_id);
        $response = $client->addEventLog($cartadd_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleCartaddEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
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

    public function testAddSingleRateEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-rate';
        $item_id = ['item_id'=>'P1000005', 'value' => 3.0];

        $rate_event = new RateEvent($customer_id, $item_id);
        $response = $client->addEventLog($rate_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultipleRateEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-multi-rates';
        $rate_actions = [
            ['item_id'=>'P1000005', 'value' => 3.0],
            ['item_id'=>'P1000006', 'value' => 4.0]
        ];

        $options = array(
            'timestamp' => time()
        );

        $rate_event = new RateEvent($customer_id, $rate_actions, $options);
        $response = $client->addEventLog($rate_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testBadRateActionTypeOnRateEventWithoutValueKeyword() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
                sprintf(Config::ARR_FORM_ERRMSG, RateEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'value' => 5.0] ] (1D array available if recording single rate action)")
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = [
            ['item_id' => 'P1000007'],
        ];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithAssociativeArray() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::NON_SEQ_ARR_ERRMSG, RateEvent::class, '__construct', 2)
        );


        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = [
            'P20007' => ['price'=> 11000, 'count'=> 3],
            'P20008' => ['price'=> 12000, 'count'=> 2],
        ];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithEmptyArray() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_ARR_ERRMSG, RateEvent::class, '__construct', 2)
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = [];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithString() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_ARR_ERRMSG, RateEvent::class, '__construct', 2)
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = 'P1234678';

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithMissingKeyword() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::ARR_FORM_ERRMSG, RateEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'value' => 5.0] ] (1D array available if recording single rate action)")
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = ['item_id' => 'P1112345'];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }

    public function testBadRateActionTypeOnRateEventWithWrongKeyword() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::ARR_FORM_ERRMSG, RateEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'value' => 5.0] ] (1D array available if recording single rate action)")
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $rate_actions = ['item_id' => 'P1112345', 'price' => 3000];

        $purchase_event = new RateEvent($customer_id, $rate_actions);
        $client->addEventLog($purchase_event);
    }



    /* ------------------- Test Custom Event ---------------------  */
    // Only test the addEventLog

    public function testAddSinglecustomEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-add-single-custom';
        $custom_event = 'search';
        $custom_action = ['item_id' => 'P1000005', 'value' => null];

        $custom_event = new CustomEvent($customer_id, $custom_event, $custom_action);
        $response = $client->addEventLog($custom_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testAddMultiplecustomEventLog() {
        $client = new ZaiClient($this->client_id, SECRET);
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

    public function testAddCustomEventLogWithIndexedArray() {
        $client = new ZaiClient($this->client_id, SECRET);
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
    public function testBadSecret() {
        $this->expectException(ZaiClientException::class);

        $bad_secret = '123456777';
        $client = new ZaiClient($this->client_id, $bad_secret);
        $customer_id = 'php-raise-error';
        $item_id = 'P1000005';

        $custom_event = new ViewEvent($customer_id, $item_id);
        $client->addEventLog($custom_event); // This should throw ZaiClientException
    }

    /**
     * @expectException InvalidArgumentException
     */
    public function testUpdateMultipleEvent() {
        $this->expectException(\InvalidArgumentException::class);
        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $item_ids = ['P1000005', 'P1000006', 'P100007'];

        $view_event = new ViewEvent($customer_id, $item_ids);
        $client->updateEventLog($view_event); // This should throw ZaiClientException
    }

    public function testBadOrdersTypeOnPurchaseEventWithoutCountKeyword() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectException(\InvalidArgumentException::class);

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $orders = [
            ['item_id' => 'P1000007', 'price'=> 11000],
        ];

        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }

    public function testBadOrdersTypeOnPurchaseEventWithAssociativeArray() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::ARR_FORM_ERRMSG, PurchaseEvent::class, '__construct', 2, "[ ['item_id' => P12345, 'price' => 50000, 'count' => 3] ] (1D array available if recording single order)")
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $orders = [
            ['P20007' => ['price'=> 11000, 'count'=> 3]],
            ['P20008' => ['price'=> 12000, 'count'=> 2]],
        ];

        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }

    public function testBadOrdersTypeOnPurchaseEventWithEmptyArray() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(Config::EMPTY_ARR_ERRMSG, PurchaseEvent::class, '__construct', 2)
        );

        $client = new ZaiClient($this->client_id, SECRET);
        $customer_id = 'php-raise-error';
        $orders = [];

        $purchase_event = new PurchaseEvent($customer_id, $orders);
        $client->addEventLog($purchase_event);
    }
}
