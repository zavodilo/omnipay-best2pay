<?php

namespace Omnipay\Best2Pay\Message;

use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Number;
use Money\Parser\DecimalMoneyParser;

/**
 * Class AuthorizeRequest
 *
 * @package Omnipay\Best2Pay\Message
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        $this->getCancelUrl();
        $this->validate('amount', 'currency', 'orderNumber', 'description', 'sector', 'returnUrl', 'password');
        $signatureString = $this->getSector()
            . $this->getAmountInteger()
            . $this->getCurrencyNumeric()
            . $this->getPassword();
        $signature = $this->buildSignature($signatureString);
        $data = [
            'signature' => $signature,
            'sector' => $this->getSector(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'reference' => $this->getOrderNumber(),
            'description' => $this->getDescription(),
            'url' => $this->getReturnUrl(),
            'failUrl' => $this->getCancelUrl(),
            'get_token' => (int) $this->getGetToken(),
        ];

        if ($amount2 = $this->getAmount2()) {
            $data['amount2'] = (int) $this->createMoney($this->getParameter('amount2'))->getAmount();;
        }

        $additionalParams = [
            'email',
            'phone',
            'mode',
            'bank_name',
            'first_name',
            'last_name',
            'address',
            'city',
            'region',
            'post_code',
            'country',
            'recurring_peri',
            'error_period',
            'error_number',
            'remind_days_before',
            'start_date',
            'end_date',
            'receipt_type',
            'lang',
        ];

        return $this->specifyAdditionalParameters($data, $additionalParams);
    }

    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return 'Register';
    }

    /**
     * Money object.
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function createMoney($amount = null)
    {
        $currencyCode = $this->getCurrency() ?: 'USD';
        $currency = new Currency($currencyCode);

        if ($amount === null) {
            return null;
        } elseif ($amount instanceof Money) {
            $money = $amount;
        } elseif (is_integer($amount)) {
            $money = new Money($amount, $currency);
        } else {
            $moneyParser = new DecimalMoneyParser($this->getCurrencies());

            $number = Number::fromString($amount);

            // Check for rounding that may occur if too many significant decimal digits are supplied.
            $decimal_count = strlen($number->getFractionalPart());
            $subunit = $this->getCurrencies()->subunitFor($currency);
            if ($decimal_count > $subunit) {
                throw new InvalidRequestException('Amount precision is too high for currency.');
            }

            $money = $moneyParser->parse((string) $number, $currency);
        }

        // Check for a negative amount.
        if (!$this->negativeAmountAllowed && $money->isNegative()) {
            throw new InvalidRequestException('A negative amount is not allowed.');
        }

        // Check for a zero amount.
        if (!$this->zeroAmountAllowed && $money->isZero()) {
            throw new InvalidRequestException('A zero amount is not allowed.');
        }

        return $money;
    }

    /**
     * Amount2 (displayed gateay amount)
     *
     * @param $amount
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getAmount2()
    {
        $money = $this->createMoney($this->getParameter('amount2'));

        if ($money !== null) {
            $moneyFormatter = new DecimalMoneyFormatter($this->getCurrencies());

            return $moneyFormatter->format($money);
        }
    }

    /**
     * Amount2.
     * @param $value
     * @return \Omnipay\Best2Pay\Message\PurchaseRequest
     */
    public function setAmount2($value): self
    {
        return $this->setParameter('amount2', $value !== null ? (string) $value : null);
    }

    /**
     * @return int
     */
    public function getOrderNumber(): int
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setOrderNumber(string $value): self
    {
        return $this->setParameter('orderNumber', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getParameter('email');
    }

    /**
     * @param bool $value
     * @return \Omnipay\Best2Pay\Message\PurchaseRequest
     */
    public function setGetToken(bool  $value): self
    {
        return $this->setParameter('get_token', $value);
    }

    /**
     * @return bool|null
     */
    public function getGetToken(): ?bool
    {
        return $this->getParameter('get_token');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPhone(string $value): self
    {
        return $this->setParameter('phone', $value);
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->getParameter('phone');
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setMode(int $value): self
    {
        return $this->setParameter('mode', $value);
    }

    /**
     * @return int|null
     */
    public function getMode(): ?int
    {
        return $this->getParameter('mode');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBlankName(string $value): self
    {
        return $this->setParameter('bank_name', $value);
    }

    /**
     * @return string|null
     */
    public function getBlankName(): ?string
    {
        return $this->getParameter('bank_name');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setFirstName(string $value): self
    {
        return $this->setParameter('first_name', $value);
    }

    /**
     * @return string|null
     */
    public function geFirstName(): ?string
    {
        return $this->getParameter('first_name');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAddressName(string $value): self
    {
        return $this->setParameter('address', $value);
    }

    /**
     * @return string|null
     */
    public function getAddressName(): ?string
    {
        return $this->getParameter('address');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCity(string $value): self
    {
        return $this->setParameter('city', $value);
    }

    /**
     * @return string|null
     */
    public function getCityName(): ?string
    {
        return $this->getParameter('city');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRegion(string $value): self
    {
        return $this->setParameter('region', $value);
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->getParameter('region');
    }

    /**
     * @param string $value
     * @return PurchaseRequest
     */
    public function setPostCode(string $value): self
    {
        return $this->setParameter('post_code', $value);
    }

    /**
     * @return string|null
     */
    public function getPostCode(): ?string
    {
        return $this->getParameter('post_code');
    }

    /**
     * @param string $value
     * @return PurchaseRequest
     */
    public function setCountry(string $value): self
    {
        return $this->setParameter('country', $value);
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->getParameter('country');
    }

    /**
     * @param string $value
     * @return PurchaseRequest
     */
    public function setRecurringPeriod(string $value): self
    {
        return $this->setParameter('recurring_period', $value);
    }

    /**
     * @return string|null
     */
    public function getRecurringPeriod(): ?string
    {
        return $this->getParameter('recurring_period');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setErrorPeriodPeriod(string $value): self
    {
        return $this->setParameter('error_period', $value);
    }

    /**
     * @return string|null
     */
    public function getErrorPeriod(): ?string
    {
        return $this->getParameter('error_period');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setErrorNumber(string $value): self
    {
        return $this->setParameter('error_number', $value);
    }

    /**
     * @return string|null
     */
    public function getErrorNumber(): ?string
    {
        return $this->getParameter('error_number');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRemindDaysBefore(string $value): self
    {
        return $this->setParameter('remind_days_before', $value);
    }

    /**
     * @return string|null
     */
    public function getRemindDaysBefore(): ?string
    {
        return $this->getParameter('remind_days_before');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function SetStartData(string $value): self
    {
        return $this->setParameter('start_date', $value);
    }

    /**
     * @return string|null
     */
    public function getStartData(): ?string
    {
        return $this->getParameter('start_date');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEndDate(string $value): self
    {
        return $this->setParameter('end_date', $value);
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->getParameter('end_date');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRecipientType(string $value): self
    {
        return $this->setParameter('receipt_type', $value);
    }

    /**
     * @return string
     */
    public function getRecipientType(): ?string
    {
        return $this->getParameter('receipt_type');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLang(string $value): self
    {
        return $this->setParameter('lang', $value);
    }

    /**
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->getParameter('lang');
    }
}
