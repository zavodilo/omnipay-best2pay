<?php

namespace Omnipay\Best2Pay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class AuthorizeResponse
 * @package Omnipay\Best2Pay\Message
 */
class PurchaseResponse extends Response implements RedirectResponseInterface
{
		/**
		 * @inheritdoc
		 */
		public function isRedirect(): bool
		{
				return $this->isSuccessful();
		}

		/**
		 * @inheritdoc
		 */
		public function getRedirectUrl(): ?string
		{
				return $this->getRequest()->getEndPoint() . 'Purchase';
		}

		/**
		 * @inheritdoc
		 */
		public function getRedirectData(): array
		{
				$result = [];
				/** @var PurchaseRequest $request */
				$request = $this->getRequest();
				$sector = $request->getSector();
				$signatureString = $sector . $this->getOrderId() . $request->getPassword();
				$result['signature'] = $request->buildSignature($signatureString);
				$result['id'] = $this->getOrderId();
				$result['sector'] = $sector;

				return $result;
		}

		/**
		 * Get the order number in the payment system. Unique within the system.
		 *
		 * @return mixed|null
		 */
		public function getOrderId(): ?int
		{
				return $this->data['id'] ?? null;
		}

		/**
		 * @inheritdoc
		 */
		public function getRedirectMethod(): string
		{
				return 'POST';
		}
}
