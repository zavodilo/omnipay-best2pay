<?php

namespace Omnipay\Best2Pay\Message;

/**
 * @inheritdoc
 */
class GooglePayRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'GooglePay/Direct';
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        $this->validate('sector', 'token', 'email', 'orderId');

        $signatureString = $this->getSector()
            . $this->getOrderId()
            . $this->getToken()
            . $this->getPassword();
        $signature = $this->buildSignature($signatureString);

        $data = [
            'sector' => $this->getSector(),
            'id' => $this->getOrderId(),
            'cryptogram' => $this->getToken(),
            'email' => $this->getEmail(),
            'preauth' => 'Y',
            'signature' => $signature,
        ];

        return $data;
    }

    /**
     * Идентификатор платежа.
     *
     * @return string|null
     */
    public function getEmail(): string
    {
        return $this->getParameter('email');
    }

    /**
     * Email.
     *
     * @param string $value Значение
     * @return $this
     */
    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Идентификатор платежа.
     *
     * @return int|null
     */
    public function getReference(): ?int
    {
        return $this->getParameter('reference');
    }

    /**
     * Идентификатор платежа.
     *
     * @param int $value Значение
     * @return $this
     */
    public function setReference(int $value): self
    {
        return $this->setParameter('reference', $value);
    }

    /**
     * Идентификатор платежа в системе best2Pay.
     *
     * @return int
     */
    public function getOrderId(): ?int
    {
        return $this->getParameter('orderId');
    }

    /**
     * Идентификатор платежа в системе best2Pay.
     *
     * @param int $value Идентификатор платежа
     *
     * @return $this
     */
    public function setOrderId(int $value): self
    {
        return $this->setParameter('orderId', $value);
    }
}
