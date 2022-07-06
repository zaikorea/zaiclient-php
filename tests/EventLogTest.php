<?php
namespace ZaiKorea\ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\PurchaseEvent;

class EventLogTest extends TestCase {
    private $client_id = 'test';
    private $client_secret = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';

    public function testAddSinglePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = "php-add-single-pruchase";
        $order = array('P1234567' => ['price'=> 11000, 'count'=> 1]);

        $purchase_event = new PurchaseEvent($customer_id, $order);
        $response_status = $client->addEventLog($purchase_event);

        self::assertSame(200, $response_status);
    }

    public function testAddMultiplePurchaseEventLog() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $customer_id = "php-add-multi-purchase";
        $orders = array(
            'P1234567' => ['price'=> 11000, 'count'=> 2],
            'P5678901' => ['price'=> 11000, 'count'=> 3]
        );

        $options = array(
            'timestamp' => time()
        );

        $purchase_event = new PurchaseEvent($customer_id, $orders, $options);
        $response_status = $client->addEventLog($purchase_event);
        self::assertSame(200, $response_status);
    }

}
