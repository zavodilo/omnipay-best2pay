<?php

namespace Omnipay\Best2Pay\Message;

/**
 * Class CallbackResponse
 *
 * @package Omnipay\Best2Pay\Message
 */
class CallbackResponse extends Response
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
     * @return string|null
     */
    public function getOperationType(): ?string
    {
        return $this->data['type'];
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->data['state'];
    }

    /**
     * @return int|null
     */
    public function getReasonCode(): ?int
    {
        return $this->data['reason_code'];
    }

    /**
     * @return int|null
     */
    public function getReference(): ?int
    {
        return $this->data['reference'];
    }
}