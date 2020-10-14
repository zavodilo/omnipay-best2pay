<?php

namespace Omnipay\Best2Pay\Message;

use Money\Formatter\DecimalMoneyFormatter;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Class AuthorizeSafeRequest
 * @package Omnipay\Best2Pay\Message
 */
class AuthorizeSafeRequest extends AuthorizeRequest
{
    public function getAmount()
    {
        $money = $this->getMoney();

        if ($money !== null) {
            $moneyFormatter = new DecimalMoneyFormatter($this->getCurrencies());

            return $moneyFormatter->format($money);
        }
    }

    /**
     * Sets the payment amount.
     *
     * @param string|null $value
     * @return $this
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value !== null ? (string) $value : null);
    }
    public function getServiceAmount(): int
    {
        return $this->getParameter('service_amount');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setServiceAmount(string $value): self
    {
        return $this->setParameter('service_amount', $value);
    }

    public function getExtraServiceAmount(): int
    {
        return $this->getParameter('extra_service_amount');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setExtraServiceAmount(string $value): self
    {
        return $this->setParameter('extra_service_amount', $value);
    }

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
            'service_amount' => $this->getServiceAmount(),
            'extra_service_amount' => $this->getExtraServiceAmount(),
        ];


        return $data;
    }
}