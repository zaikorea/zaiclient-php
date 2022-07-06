<?php
namespace ZaiKorea\ZaiClient;

use Error;
use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\PurchaseEvent;
use ZaiKorea\ZaiClient\Requests\ViewEvent;
use ZaiKorea\ZaiClient\Requests\LikeEvent;
use ZaiKorea\ZaiClient\Requests\CartaddEvent;
use ZaiKorea\ZaiClient\Requests\RateEvent;
use ZaiKorea\ZaiClient\Requests\CustomEvent;

class EventLogTest extends TestCase {
    private $client_id = 'test';
    private $client_secret = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';


    /* --------------------- Test Purchase Event --------------------  */
    // Tests addEventLog, updateEventLog, deleteEventLog

    public function testAddSinglePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-purchase';
        $order = array('P1000009' => ['price'=> 11000, 'count'=> 1]);

        $purchase_event = new PurchaseEvent($customer_id, $order);
        $response_status = $client->addEventLog($purchase_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultiplePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-purchase';
        $order = array(
            'P1234567' => ['price'=> 11000, 'count'=> 2],
            'P5678901' => ['price'=> 11000, 'count'=> 3]
        );

        $options = array(
            'timestamp' => time()
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response_status = $client->addEventLog($purchase_event);
        self::assertSame(200, $response_status);
    }

    public function testUpdatePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-purchase';
        $order = array(
            'P1000009' => ['price'=> 14000, 'count'=> 1],
        );

        $options = array(
            'timestamp' => time()
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response_status = $client->updateEventLog($purchase_event);
        self::assertSame(200, $response_status);
    }

    public function testDeletePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-purchase';
        $order = array(
            'P1000007' => ['price'=> 11000, 'count'=> 2],
            'P1000008' => ['price'=> 11000, 'count'=> 3]
        );

        $options = array(
            'timestamp' => time()
        );

        $purchase_event = new PurchaseEvent($customer_id, $order, $options);
        $response_status = $client->addEventLog($purchase_event);
        if ($response_status != 200)
            throw new Error('Something went wrong while testing Delete Purchase Event');

        $response_status = $client->deleteEventLog($purchase_event);
        self::assertSame(200, $response_status);
    }

    /* ----------------------- Test View Event -----------------------  */
    // From here on only test the addEventLog

    public function testAddSingleViewEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-view';
        $item_id = 'P1000005';

        $view_event = new ViewEvent($customer_id, $item_id);
        $response_status = $client->addEventLog($view_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultipleViewEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-views';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $view_event = new ViewEvent($customer_id, $item_ids, $options);
        $response_status = $client->addEventLog($view_event);

        self::assertSame(200, $response_status);
    }

    /* ----------------------- Test Like Event -----------------------  */
    // Only test the addEventLog

    public function testAddSingleLikeEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-like';
        $item_id = 'P1000005';

        $like_event = new LikeEvent($customer_id, $item_id);
        $response_status = $client->addEventLog($like_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultipleLikeEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-likes';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $like_event = new ViewEvent($customer_id, $item_ids, $options);
        $response_status = $client->addEventLog($like_event);

        self::assertSame(200, $response_status);
    }

    /* ------------------- Test Cartdd Event ---------------------  */
    // Only test the addEventLog
    // TODO: test delete

    public function testAddSingleCartaddEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-cartadd';
        $item_id = 'P1000005';

        $cartadd_event = new CartaddEvent($customer_id, $item_id);
        $response_status = $client->addEventLog($cartadd_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultipleCartaddEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-cartadds';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $cartadd_event = new CartaddEvent($customer_id, $item_ids, $options);
        $response_status = $client->addEventLog($cartadd_event);

        self::assertSame(200, $response_status);
    }


    /* ------------------- Test Rate Event ---------------------  */
    // Only test the addEventLog

    public function testAddSingleRateEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-rate';
        $item_id = 'P1000005';

        $rate_event = new RateEvent($customer_id, $item_id);
        $response_status = $client->addEventLog($rate_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultipleRateEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-rates';
        $item_ids = ['P1000000', 'P1000001'];

        $options = array(
            'timestamp' => time()
        );

        $rate_event = new RateEvent($customer_id, $item_ids, $options);
        $response_status = $client->addEventLog($rate_event);

        self::assertSame(200, $response_status);
    }

    /* ------------------- Test Custom Event ---------------------  */
    // Only test the addEventLog

    public function testAddSinglecustomEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-single-custom';
        $custom_event = 'search';
        $custom_action = ['P1000005' => 4];

        $custom_event = new CustomEvent($customer_id, $custom_event, $custom_action);
        $response_status = $client->addEventLog($custom_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultiplecustomEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = 'php-add-multi-customs';
        $custom_event = 'search';
        $custom_actions = [
            'P1000003' => null,
            'P1000002' => null
        ];

        $options = array(
            'timestamp' => time()
        );

        $custom_event = new CustomEvent($customer_id, $custom_event, $custom_actions, $options);
        $response_status = $client->addEventLog($custom_event);

        self::assertSame(200, $response_status);
    }
}
