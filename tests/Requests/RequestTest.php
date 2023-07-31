<?php
namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use ZaiClient\Tests\TestUtils;
use ZaiClient\Requests\Request;




class RequestTest extends TestCase
{
    function testRequestGetPath()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('NotImplementedError');

        $method = 'POST';
        $base_url = 'https://test.zai-dev.com';
        $request = new Request($method, $base_url);

        $request->getPath('Test_Client_Id');
    }


    function testRequestGetPayload()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('NotImplementedError');

        $method = 'POST';
        $base_url = 'https://test.zai-dev.com';
        $request = new Request($method, $base_url);

        $request->getPayload('Test_Client_Id');
    }

    function testRequestGetQueryParam()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('NotImplementedError');

        $method = 'POST';
        $base_url = 'https://test.zai-dev.com';
        $request = new Request($method, $base_url);

        $request->getQueryParam();
    }
}
