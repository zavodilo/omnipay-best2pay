<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Ответ платёжной системы на запрос makeApplePay
 */
class MakeApplePayResponse extends Response
{
    /**
     * @inheritdoc
     */
    public function getOrderId(): ?int
    {
        return $this->data['order_id'] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): ?string
    {
        if ($this->getCode() === 0) {
            return (!$this->isSuccessful() && $this->data['message']) ? $this->data['message'] : null;
        }

        return parent::getMessage();
    }

    /**
     * @inheritdoc
     */
    public function getOperationId(): ?int
    {
        return $this->data['id'] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && ((int)$this->data['reason_code'] === 1);
    }
}
