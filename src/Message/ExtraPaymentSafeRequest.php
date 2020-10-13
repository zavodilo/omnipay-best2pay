<?php

namespace Omnipay\Best2Pay\Message;

class ExtraPaymentSafeRequest extends CaptureRequest
{
    /**
     * @return int
     */
    public function getOriginatorId(): int
    {
        return $this->getParameter('originator_id');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOriginatorId(int $value): self
    {
        return $this->setParameter('originator_id', $value);
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        $this->validate('sector', 'originator_id', 'id', 'amount', 'currency', 'password');
        $signatureString = $this->getSector()
            . $this->getOriginatorId()
            . $this->getId()
            . $this->getAmountInteger()
            . $this->getCurrencyNumeric()
            . $this->getPassword();
        $signature = $this->buildSignature($signatureString);

        return [
            'id' => $this->getId(),
            'originator_id' => $this->getOriginatorId(),
            'sector' => $this->getSector(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'signature' => $signature,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'SafeExtraPayment';
    }

}