<?php

namespace Omnipay\Best2Pay\Message;

class CaptureRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'Complete';
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
    public function getData(): array
    {
        $this->validate('sector', 'id', 'amount', 'currency', 'password');
        $signatureString = $this->getSector()
            . $this->getId()
            . $this->getAmountInteger()
            . $this->getCurrencyNumeric()
            . $this->getPassword();
        $signature = $this->buildSignature($signatureString);

        return [
            'id' => $this->getId(),
            'sector' => $this->getSector(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'signature' => $signature,
        ];
    }
}