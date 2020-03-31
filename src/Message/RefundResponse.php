<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Class RefundResponse
 * @package Omnipay\Best2Pay\Message
 */
class RefundResponse extends Response
{
    /**
     * Get the order number in the payment system. Unique within the system.
     *
     * @return mixed|null
     */
    public function getOrderId(): ?int
    {
        return $this->data['order_id'] ?? null;
    }

    /**
     * Get the operation number in the payment system. Unique within the system.
     *
     * @return mixed|null
     */
    public function getOperationId(): ?int
    {
        return $this->data['id'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && ((int)$this->data['reason_code'] === 1);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): ?string
    {
        if ($this->isSuccessful()) {
            return null;
        }
        $message = '';
        if (isset($this->data['description'])) {
            return $this->data['description'];
        }
        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        return $message;
    }
}