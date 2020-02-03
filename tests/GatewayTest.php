<?php

namespace Omnipay\Best2Pay\Tests;

use Omnipay\Best2Pay\Message\GooglePayRequest;
use Omnipay\Best2Pay\Message\MakeApplePayRequest;
use Omnipay\Best2Pay\Message\RefundRequest;
use Omnipay\Best2Pay\Gateway;
use Omnipay\Best2Pay\Message\PurchaseRequest;
use Omnipay\Best2Pay\Message\CaptureRequest;
use Omnipay\Best2Pay\Message\OrderStatusRequest;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class GatewayTest
 * @package Omnipay\Best2Pay\Tests
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * Gateway
     *
     * @var Gateway
     */
    protected $gateway;

    /**
     * Gateway password
     *
     * @var string
     */
    protected $password;


    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->password = uniqid('', true);

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setTestMode(true)
            ->setPassword($this->password)
            ->setSector(951);
    }

    public function testAuthorize(): void
    {
        $this->assertTrue($this->gateway->supportsAuthorize());
        $this->assertTrue(method_exists($this->gateway, 'authorize'));
        $this->assertInstanceOf(PurchaseRequest::class, $this->gateway->authorize());
    }

    public function testRefund(): void
    {
        $this->assertTrue($this->gateway->supportsRefund());
        $this->assertTrue(method_exists($this->gateway, 'refund'));
        $this->assertInstanceOf(RefundRequest::class, $this->gateway->refund());
    }

    public function testPurchase(): void
    {
        $this->assertTrue($this->gateway->supportsPurchase());
        $this->assertTrue(method_exists($this->gateway, 'purchase'));
        $this->assertInstanceOf(PurchaseRequest::class, $this->gateway->purchase());
    }

    public function testCapture(): void
    {
        $this->assertTrue($this->gateway->supportsCapture());
        $this->assertTrue(method_exists($this->gateway, 'capture'));
        $this->assertInstanceOf(CaptureRequest::class, $this->gateway->capture());
    }

    public function testStatus(): void
    {
        $this->assertTrue($this->gateway->supportsOrderStatus());
        $this->assertTrue(method_exists($this->gateway, 'orderStatus'));
        $this->assertInstanceOf(OrderStatusRequest::class, $this->gateway->orderStatus());
    }

    public function testApplePay(): void
    {
        $this->assertTrue($this->gateway->supportMakeApplePay());
        $this->assertTrue(method_exists($this->gateway, 'makeApplePay'));
        $this->assertInstanceOf(MakeApplePayRequest::class, $this->gateway->makeApplePay());
    }

    public function testGooglePay(): void
    {
        $this->assertTrue($this->gateway->supportGooglePay());
        $this->assertTrue(method_exists($this->gateway, 'makeGooglePay'));
        $this->assertInstanceOf(GooglePayRequest::class, $this->gateway->makeGooglePay());
    }
}
