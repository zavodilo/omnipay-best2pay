<?php

namespace Omnipay\Best2Pay;

use Omnipay\Best2Pay\Message\AuthorizeRequest;
use Omnipay\Best2Pay\Message\PurchaseRequest;
use Omnipay\Best2Pay\Message\CaptureRequest;
use Omnipay\Best2Pay\Message\OrderStatusRequest;
use Omnipay\Best2Pay\Message\RefundRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Gateway
 * @package Omnipay\Best2Pay\
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
		/**
		 * @inheritdoc
		 */
		public function getName(): string
		{
				return 'Best2Pay';
		}

		/**
		 * @inheritdoc
		 */
		public function getShortName(): string
		{
				return 'Best2Pay';
		}

		/**
		 * @inheritdoc
		 */
		public function getDefaultParameters(): array
		{
				return [
						'password' => '',
						'testMode' => false,
						'endPoint' => 'https://best2pay.net/webapi/',
				];
		}

		/**
		 * @inheritdoc
		 */
		public function setTestMode($testMode): self
		{
				$this->setEndPoint($testMode ? 'https://test.best2pay.net/webapi/' : 'https://best2pay.net/webapi/');

				return $this->setParameter('testMode', $testMode);
		}

		/**
		 * Get sector id
		 *
		 * @return int
		 */
		public function getSector(): int
		{
				return $this->getParameter('sector');
		}

		/**
		 * Set sector id
		 *
		 * @param int $value
		 * @return Gateway
		 */
		public function setSector(int $value): self
		{
				return $this->setParameter('sector', $value);
		}

		/**
		 * Get endpoint URL
		 *
		 * @return string
		 */
		public function getEndPoint(): string
		{
				return $this->getParameter('endPoint');
		}

		/**
		 * Set endpoint URL
		 *
		 * @param string $endPoint
		 * @return $this
		 */
		public function setEndPoint($endPoint): self
		{
				return $this->setParameter('endPoint', $endPoint);
		}

		/**
		 * Get gateway password
		 *
		 * @return string
		 */
		public function getPassword(): string
		{
				return $this->getParameter('password');
		}

		/**
		 * Set gateway password
		 *
		 * @param string $password
		 * @return $this
		 */
		public function setPassword($password): self
		{
				return $this->setParameter('password', $password);
		}

		/**
		 * Get the request return URL
		 *
		 * @return string
		 */
		public function getReturnUrl(): string
		{
				return $this->getParameter('returnUrl');
		}

		/**
		 * Sets the request return URL
		 *
		 * @param string $value
		 * @return $this
		 */
		public function setReturnUrl($value): self
		{
				return $this->setParameter('returnUrl', $value);
		}

		/**
		 * Request for order registration with pre-authorization
		 *
		 * @param array $options array of options
		 * @return RequestInterface
		 */
		public function purchase(array $options = []): RequestInterface
		{
				return $this->createRequest(PurchaseRequest::class, $options);
		}

		/**
		 * Request for order registration without pre-authorization
		 *
		 * @param array $options array of options
		 * @return RequestInterface
		 */
		public function authorize(array $options = []): RequestInterface
		{
				return $this->createRequest(AuthorizeRequest::class, $options);
		}

		/**
		 * Order completion payment request
		 *
		 * @param array $options
		 * @return RequestInterface
		 */
		public function capture(array $options = []): RequestInterface
		{
				return $this->createRequest(CaptureRequest::class, $options);
		}

		/**
		 * Refund order request
		 *
		 * @param array $options
		 * @return RequestInterface
		 */
		public function refund(array $options = []): RequestInterface
		{
				return $this->createRequest(RefundRequest::class, $options);
		}

		/**
		 * Order status request
		 *
		 * @param array $options
		 * @return RequestInterface
		 */
		public function orderStatus(array $options = []): RequestInterface
		{
				return $this->createRequest(OrderStatusRequest::class, $options);
		}

		/**
		 * @return bool
		 */
		public function supportsOrderStatus(): bool
		{
				return method_exists($this, 'orderStatus');
		}

		/**
		 * Order status request
		 *
		 * @param array $options
		 * @return RequestInterface
		 */
		public function completeAuthorize(array $options = []): RequestInterface
		{
				return $this->createRequest(OrderStatusRequest::class, $options);
		}


		public function __call($name, $arguments)
		{
				// TODO: Implement @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
				// TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
				// TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
				// TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
		}
}
