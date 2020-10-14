<?php

namespace Omnipay\Best2Pay\Message;


/**
 * Class AuthorizeSafeRequest
 * @package Omnipay\Best2Pay\Message
 */
class AuthorizeSafeRequest extends AuthorizeRequest
{
    public function getData(): array
    {
        $this->getCancelUrl();
        $this->validate('amount', 'currency', 'orderNumber', 'description', 'sector', 'returnUrl', 'password');
        $signatureString = $this->getSector()
            . $this->getAmountInteger()
            . $this->getCurrencyNumeric()
            . $this->getPassword();
        $signature = $this->buildSignature($signatureString);
        $data = [
            'signature' => $signature,
            'sector' => $this->getSector(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'reference' => $this->getOrderNumber(),
            'description' => $this->getDescription(),
            'url' => $this->getReturnUrl(),
            'failUrl' => $this->getCancelUrl(),
            'get_token' => (int) $this->getGetToken(),
            'extra_service_amount' => $this->getAmountInteger(),
        ];

        return $data;
    }
}