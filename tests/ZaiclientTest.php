<?php

/**
 * ZaiClientTest
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

class ZaiclientTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
    private $add_event_msg = 'The given event was added successfully.';

    public function testClient()
    {

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = 'php-testClient';
        $item_id = 'P1000005';
        $product_detail_view_event = new ProductDetailViewEvent($user_id, $item_id);
        $response = $client->addEventLog($product_detail_view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testClientWithWrongTimeoutOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $options = [
            'connection_timeout' => "13",
            'read_timeout' => "2"
        ];

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
    }

    public function testClientWithTimeoutOptions()
    {
        $options = [
            'connect_timeout' => 60,
            'read_timeout' => 60
        ];
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
        $user_id = 'php-add-single-productdetailview';
        $item_id = 'P1000005';

        $product_detail_view_event = new ProductDetailViewEvent($user_id, $item_id);
        $response = $client->addEventLog($product_detail_view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

}

