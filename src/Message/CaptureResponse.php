<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Class CaptureResponse
 * @package Omnipay\Best2Pay\Message
 */
class CaptureResponse extends Response
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

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
       return parent::isSuccessful() && ((int)$this->data['reason_code'] === 1);
    }
}
