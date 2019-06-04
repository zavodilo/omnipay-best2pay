<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Class OrderStatusRequest
 * @package Omnipay\Best2Pay\Message
 */
class OrderStatusRequest extends AbstractRequest
{
		/**
		 * @inheritdoc
		 */
		public function getAction(): string
		{
				return 'Order';
		}

		/**
		 * @return int
		 */
		public function getId(): int
		{
				return $this->getParameter('id');
		}

		/**
		 * @param $value
		 * @return $this
		 */
		public function setId(int $value): self
		{
				return $this->setParameter('id', $value);
		}

		/**
		 * @inheritdoc
		 */
		public function getData()
		{
				$this->validate('sector', 'id', 'password');
				$signatureString = $this->getSector() . $this->getId() . $this->getPassword();
				$signature = $this->buildSignature($signatureString);

				return [
						'id' => $this->getId(),
						'sector' => $this->getSector(),
						'signature' => $signature,
				];
		}
}