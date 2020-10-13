<?php

namespace Omnipay\Best2Pay\Message;

class RefundSafeRequest extends RefundRequest
{
    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'SafeReverse';
    }

}