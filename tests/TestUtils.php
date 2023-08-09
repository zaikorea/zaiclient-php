<?php
namespace ZaiClient\Tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TestUtils
{
    /**
     * A Wrapper function to get a constant like variable that is returned by json_encode.
     * `const DEFAULT_BODY = json_encode(['message' => 'success']);` causes error
     * @return string
     */
    static function getDefaultResponseBody()
    {
        return json_encode(['message' => 'success']);
    }

    static function generateRandomString($length = 101)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        return $random_string;
    }

    static function generateRandomArrayOfUuid($length = 10)
    {
        $array = [];
        for ($i = 0; $i < $length; $i++) {
            array_push($array, TestUtils::generateUuid());
        }
        return $array;
    }

    static function generateUuid()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }

    /**
     * A fixture like function to create a mock http client
     * @param TestCase $test_class
     */
    static function createMockHttpClient($test_class, $mock_body = null)
    {
        $mockHttpClient = $test_class->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();

        $body = is_null($mock_body) ? TestUtils::getDefaultResponseBody() : $mock_body;

        $mockHttpClient->expects($test_class->any())
            ->method('request')
            ->willReturn(new Response(200, [], $body));

        return $mockHttpClient;
    }

    static function getEmptyItemRequestPayload()
    {
        return [
            "item_id" => null,
            "item_name" => null,
            "category_id_1" => null,
            "category_name_1" => null,
            "category_id_2" => null,
            "category_name_2" => null,
            "category_id_3" => null,
            "category_name_3" => null,
            "brand_id" => null,
            "brand_name" => null,
            "description" => null,
            "created_timestamp" => null,
            "updated_timestamp" => null,
            "is_active" => null,
            "is_soldout" => null,
            "promote_on" => null,
            "item_group" => null,
            "rating" => null,
            "price" => null,
            "click_counts" => null,
            "purchase_counts" => null,
            "image_url" => null,
            "item_url" => null,
            "miscellaneous" => null,
        ];
    }

    // TODO: You might need to filter null values

}
