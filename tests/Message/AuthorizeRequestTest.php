<?php

namespace Omnipay\Best2Pay\Tests\Message;

use Omnipay\Best2Pay\Message\AuthorizeRequest;
use Omnipay\Best2Pay\Message\AuthorizeResponse;
use Omnipay\Common\Message\RequestInterface;

require_once __DIR__ . '/PurchaseRequestTest.php';

/**
 * Class AuthorizeRequestTest
 * @package Omnipay\Best2Pay\tests\Message\Mock
 */
class AuthorizeRequestTest extends PurchaseRequestTest
{
    /**
     * @return AuthorizeRequest
     */
    protected function getRequestClass(): RequestInterface
    {
        return new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * Test send success response
     *
     * @return mixed
     */
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RegisterRequestSuccess.txt');

        /** @var AuthorizeResponse $response */
        $this->request->setPassword($this->password);
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals($response->getRedirectMethod(), 'POST');
        $this->assertEquals(
            [
                'signature' => 'NGE4NDUyNzZkNDE0MjMzZmI1NmI5MGQzMTEwY2E5YTQ=',
                'id' => '486800',
                'sector' => 222
            ],
            $response->getRedirectData()
        );
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertEquals($response->getOrderId(), 486800);
        $this->assertEquals(
            $response->getRedirectUrl(),
            'https://endpoint.test/Authorize'
        );
    }

}