<?php

namespace Omnipay\Best2Pay\Tests\Message;

use Omnipay\Best2Pay\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

/**
 * Class AbstractRequestTest
 * @package Omnipay\Sberbank\Tests\Message
 */
abstract class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest
     */
    protected $request;

    /**
     * Gateway password
     *
     * @var string
     */
    protected $password;


    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->request = $this->getRequestClass();
        $this->request->initialize($this->getRequestParameters());
    }

    /**
     * Test set Data
     *
     * @return mixed
     */
    abstract public function testData();

    /**
     * Test send success response
     *
     * @return mixed
     */
    abstract public function testSendSuccess();

    /**
     * Test send fail response
     *
     * @return mixed
     */
    abstract public function testSendError();

    /**
     * Array of request parameters to successfully build request object
     *
     * @return array
     */
    abstract protected function getRequestParameters();

    /**
     * Get request class
     *
     * @return AbstractRequest
     */
    abstract protected function getRequestClass();

    /**
     * Test request action
     *
     * @return string
     */
    abstract protected function testGetAction();
}
