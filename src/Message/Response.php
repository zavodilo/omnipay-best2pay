<?php

namespace Omnipay\Best2Pay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Response
 * @package Omnipay\Best2Pay\Message
 */
class Response extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): ?string
    {
        return (!$this->isSuccessful() && $this->data['description']) ? $this->data['description'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): ?int
    {
        return $this->data['code'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->getCode() === null;
    }
}