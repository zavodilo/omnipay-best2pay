<?php

namespace Omnipay\Best2Pay\Message;

class CaptureSafeRequest extends CaptureRequest
{
    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'SafeDepositComplete';
    }

}