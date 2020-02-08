<?php
namespace Omnipay\Best2Pay\Tests\Message;

use Omnipay\Best2Pay\Message\MakeApplePayRequest;
use Omnipay\Common\Message\RequestInterface;

class MakeApplePayRequestTest extends AbstractRequestTest
{
    /**
     * Тест метода 'getAction'.
     */
    public function testGetAction(): void
    {
        $this->assertEquals('MakeApplePay', $this->request->getAction());
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
        $this->assertEquals(
            [
                'amount' => 100 * 100,
                'sector' => 10,
                'currency' => '643',
                'paymentToken' => 'test',
                'preauth' => 'Y',
                'id' => 21312,
                'description' => 'test desc',
                'reference' => 1,
            ],
            $this->request->getData()
        );
    }

    /**
     * @inheritdoc
     */
    protected function getRequestClass(): RequestInterface
    {
        return new MakeApplePayRequest($this->getHttpClient(), $this->getHttpRequest());
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
            'currency' => 'RUB',
            'token' => 'test',
            'order_id' => 21312,
            'reference' => 1,
            'description' => 'test desc',
            'endPoint' => 'https://endpoint.test/'
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
        $this->setMockHttpResponse('MakeApplePayRequestError.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(
            'test',
            $response->getData()['description']
        );
    }
}
