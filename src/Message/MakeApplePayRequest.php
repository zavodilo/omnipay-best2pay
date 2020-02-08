<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Запрос на авторизацию через applePay.
 */
class MakeApplePayRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'MakeApplePay';
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        $this->validate('amount', 'currency', 'sector', 'token');

        $data = [
            'sector' => $this->getSector(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'paymentToken' => $this->getToken(),
            'description' => $this->getDescription(),
            'reference' => $this->getReference(),
            'preauth' => 'Y',
        ];

        if ($id = $this->getOrderId()) {
            $data['id'] = $id;
        }

        return $data;
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
