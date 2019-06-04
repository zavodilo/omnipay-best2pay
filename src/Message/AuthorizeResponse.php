<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Class AuthorizeResponse
 * @package Omnipay\Best2Pay\Message
 */
class AuthorizeResponse extends PurchaseResponse
{
		/**
		 * @inheritdoc
		 */
		public function getRedirectUrl(): ?string
		{
				return $this->getRequest()->getEndPoint() . 'Authorize';
		}
}