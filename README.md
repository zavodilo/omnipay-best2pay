# Best2Pay payments for PHP 


# Introduction
This package supports PHP 7.1 and higher 

# Download

## Composer 

```
// This assumes that you have composer installed globally
composer require redkooala/omnipay-best2pay
```

## Solving problems with minimal stability

Add to your composer.json

```json
{
  "minimum-stability":"dev",
  "prefer-stable": true
}

```

# Simple Example

```php
use Omnipay\Omnipay;

// Setup payment gateway
$gateway = Omnipay::create('Best2Pay');

// Set params for authorize request
$gateway->authorize(
    [
       'orderNumber' => $localOrderNumber, // local order number
       'amount' => $order_amount, // The amount of payment (you can use decimal with 2 precisions for copecs or string equal to decimal)
       'returnUrl' => $callback_url // succesfull callback url
       'currency' => 'RUB',
       'description' => 'merchant order description',
    ]
);
