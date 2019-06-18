<?php

namespace Omnipay\Best2Pay\Tests\Message;

use Omnipay\Best2Pay\Message\PurchaseRequest;
use Omnipay\Best2Pay\Message\PurchaseResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class AuthorizeRequestTest
 * @package Omnipay\Sberbank\Tests\Message
 */
class PurchaseRequestTest extends AbstractRequestTest
{
    /**
     * Amount to pay
     *
     * @var float
     */
    protected $amount;

    /**
     * Password
     *
     * @var string
     */
    protected $password;

    /**
     * Success url
     *
     * @var string
     */
    protected $returnUrl;

    /**
     * Local order number
     *
     * @var string
     */
    protected $orderNumber;

    /**
     * Description merchant order
     *
     * @var string
     */
    protected $description;

    /**
     * Currency
     *
     * @var string
     */
    protected $currency;

    /**
     * Sector
     *
     * @var int
     */
    protected $sector;

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function setUp()
    {
        $this->amount = 500;
        $this->orderNumber = random_int(1, 100);
        $this->returnUrl = 'https://test.com/' . uniqid('', true);
        $this->currency = 'RUB';
        $this->description = 'test description';
        $this->sector = 222;
        $this->password = 'qwprc1';

        parent::setUp();
    }


    /**
     * @inheritdoc
     */
    protected function getRequestClass(): RequestInterface
    {
        return new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @inheritdoc
     */
    public function testGetAction()
    {
        $this->assertEquals('Register', $this->request->getAction());
    }

    /**
     * @inheritdoc
     */
    protected function getRequestParameters(): array
    {
        return [
            'orderNumber' => $this->orderNumber,
            'sector' => $this->sector,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'password' => $this->password,
            'returnUrl' => $this->returnUrl,
            'endPoint' => 'https://endpoint.test/'
        ];
    }

    /**
     * @inheritdoc
     */
    public function testData(): void
    {
        $data = $this->request->getData();
        $this->assertSame('MjdjZjA2MTM2MGFiOWU1NjZlMTQ4MWM0OTg3NmU3ZDg=', $data['signature']);
        $this->assertSame(222, $data['sector']);
        $this->assertSame(50000, $data['amount']);
        $this->assertSame($this->getRequestParameters()['orderNumber'], $data['reference']);
        $this->assertSame($this->getRequestParameters()['description'], $data['description']);
        $this->assertSame($this->getRequestParameters()['returnUrl'], $data['url']);

        $this->request
            ->setLanguage($lang = 'ru')
            ->setCancelUrl($cancelUrl = 'https://test.com/fail')
            ->setDescription($description = 'test 123')
            ->setAmount(500)
            ->setCurrency('RUB')
            ->setEmail($email = 'test@gmail.com');

        $data = $this->request->getData();
        $this->assertEquals($data['lang'], $lang);
        $this->assertEquals($data['failUrl'], $cancelUrl);
        $this->assertEquals($data['currency'], 643);
        $this->assertEquals($data['description'], $description);
        $this->assertEquals($data['email'], $email);
    }

    /**
     * Test send success response
     *
     * @return mixed
     */
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RegisterRequestSuccess.txt');

        /** @var PurchaseResponse $response */
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
        $this->assertSame(0, $response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertEquals($response->getOrderId(), 486800);
        $this->assertEquals(
            $response->getRedirectUrl(),
            'https://endpoint.test/Purchase'
        );
    }

    /**
     * @inheritdoc
     */
    public function testSendError()
    {
        $this->setMockHttpResponse('RegisterRequestError.txt');
        $this->request->setPassword($this->password);
        /** @var PurchaseResponse $response */
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals($response->getCode(), 136);
        $this->assertEquals($response->getMessage(),
            'Ошибка: неправильный код валюты. Пожалуйста, обратитесь в свой Интернет-магазин.');
    }

    public function testInvalidStatus(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('invalid response status');
        $this->setMockHttpResponse('InvalidHttpStatusResponse.txt');
        $response = $this->request->send();
    }
}
