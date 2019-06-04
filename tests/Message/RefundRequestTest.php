<?php

namespace Omnipay\Best2Pay\Tests\Message;

use Omnipay\Best2Pay\Message\RefundRequest;
use Omnipay\Best2Pay\Message\RefundResponse;

/**
 * Class RefundRequestTest
 * @package Omnipay\Best2Pay\Tests\Message
 */
class RefundRequestTest extends AbstractRequestTest
{
    /**
     * Amount
     *
     * @var string
     */
    protected $amount;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->amount = 500;
        $this->currency = 'RUB';
        $this->sector = 222;
        $this->password = 'qwprc1';
        $this->id = 400;

        parent::setUp();
    }

    /**
     * Get request class
     *
     * @return string
     */
    protected function getRequestClass()
    {
        return new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * Array of request parameters to successfully build request object
     *
     * @return array
     */
    protected function getRequestParameters()
    {
        return [
            'sector' => $this->sector,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'password' => $this->password,
            'id' => $this->id,
            'endPoint' => 'https://endpoint.test/',
        ];
    }

    /**
     * Test set Data
     *
     * @return mixed
     */
    public function testData()
    {
        $data = $this->request->getData();
        $this->assertEquals(
            [
                'id' => 400,
                'sector' => 222,
                'currency' => '643',
                'signature' => 'OTc3OWVjNzU3NGIzM2U2YTY0Y2FkNTM1ZTAzYTgxOWU=',
                'amount' => $this->amount * 100,
            ],
            $data
        );
    }

    /**
     * Test send success response
     *
     * @return mixed
     */
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundRequestSuccess.txt');

        $this->request->setPassword($this->password);
        /** @var RefundResponse $response */
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response->getCode(), 0);
        $this->assertEquals($response->getOrderId(), 487016);
        $this->assertEquals($response->getOperationId(), 359999);
    }

    /**
     * Test send fail response
     *
     * @return mixed
     */
    public function testSendError()
    {
        $this->setMockHttpResponse('RefundRequestError.txt');

        /** @var RefundResponse $response */
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals($response->getCode(), 133);
        $this->assertEquals($response->getMessage(), 'Incorrect order state for specified operation');
    }

    /**
     * @inheritdoc
     */
    public function testGetAction()
    {
        $this->assertEquals('Reverse', $this->request->getAction());
    }
}