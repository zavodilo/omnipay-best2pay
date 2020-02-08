<?php


namespace Omnipay\Best2Pay\Tests\Message;

use Omnipay\Best2Pay\Message\GooglePayRequest;
use Omnipay\Common\Message\RequestInterface;


class GooglePayRequestTest extends AbstractRequestTest
{
    /**
     * Тест метода 'getAction'.
     */
    public function testGetAction(): void
    {
        $this->assertEquals('GooglePay/Direct', $this->request->getAction());
    }

    /**
     * Тест метода 'getHeaders'.
     */
    public function testGetHeaders(): void
    {
        $this->assertEquals(['content-type' => 'application/x-www-form-urlencoded'], $this->request->getHeaders());
    }

    /**
     * Тест метода 'getData'.
     */
    public function testData(): void
    {
        $this->assertTrue(true);
        $this->assertEquals(
            [
                'sector' => 10,
                'cryptogram' => 'test',
                'preauth' => 'Y',
                'id' => 21312,
                'email' => 'test@gmail.com',
                'signature' => 'ZjBkMWM0YTg3NjM4MzQwM2ViODFjZGMxMmI2MzM5MmE=',
            ],
            $this->request->getData()
        );
    }

    /**
     * @inheritdoc
     */
    protected function getRequestClass(): RequestInterface
    {
        return new GooglePayRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * Параметры запроса.
     *
     * @return array
     */
    protected function getRequestParameters(): array
    {
        return [
            'amount' => 100,
            'sector' => 10,
            'token' => 'test',
            'orderId' => 21312,
            'endPoint' => 'https://endpoint.test/',
            'email' => 'test@gmail.com',
            'password' => 123,
        ];
    }

    /**
     * Test send success response
     *
     * @return mixed
     */
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('MakeApplePayRequestSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(487016, $response->getData()['order_id']);
    }

    /**
     * Test send fail response
     *
     * @return mixed
     */
    public function testSendError()
    {
        $this->assertTrue(true);
        $this->setMockHttpResponse('MakeApplePayRequestError.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(
            'test',
            $response->getData()['description']
        );
    }
}