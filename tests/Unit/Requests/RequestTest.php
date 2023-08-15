<?php
namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;
use BadMethodCallException;
use ZaiClient\Tests\TestUtils;
use ZaiClient\Requests\Request;




class RequestTest extends TestCase
{
    public function testRequestGetPath()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('NotImplementedError');

        $method = 'POST';
        $base_url = 'https://test.zai-dev.com';
        $request = new Request($method, $base_url);

        $request->getPath('Test_Client_Id');
    }


    public function testRequestGetPayload()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('NotImplementedError');

        $method = 'POST';
        $base_url = 'https://test.zai-dev.com';
        $request = new Request($method, $base_url);

        $request->getPayload('Test_Client_Id');
    }

    public function testRequestGetQueryParam()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('NotImplementedError');

        $method = 'POST';
        $base_url = 'https://test.zai-dev.com';
        $request = new Request($method, $base_url);

        $request->getQueryParams();
    }
}
