# Buckaroo
> Buckaroo payment provider implementation classes


## Example
You can see a working example of the code in the [Soneritics/Buckaroo-Example](https://github.com/Soneritics/Buckaroo-Example) repository.

### Sneak preview of the code example :-)
#### TransactionRequest (Start of the payment)
```php
$gateway = new \Buckaroo\Gateways\Test;
$transactionRequest = new \Buckaroo\ServiceOperations\TransactionRequest;
$buckaroo = new \Buckaroo\Buckaroo($gateway, $websiteKey, $secretKey);

$transactionRequest
    ->setCurrency(new \Buckaroo\Currency\EUR)
    ->setPaymentMethod(new \Buckaroo\PaymentMethods\iDeal)
    ->setAmount(12.5)
    ->setInvoiceNumber('Test-' . time())
    ->setReturnURL($returnURL)
    ->setCancelURL($returnURL)
    ->setRejectURL($returnURL)
    ->setErrorURL($returnURL);

$response = $buckaroo->performServiceOperation($transactionRequest);

try {
    $redirectURL = $response->getField('BRQ_REDIRECTURL');
    header("Location: {$redirectURL}");
    die;
} catch (Exception $ex) {
    print_r($response->getFieldList());
    echo 'ERROR: Something went wrong: ' . $ex->getMessage();
}
```

#### TransactionStatus (return page)
``` php
try {
    $transactionStatusResponse = new \Buckaroo\Response\TransactionStatusResponse($_POST, $secretKey);

    if ($transactionStatusResponse->getStatus() === \Buckaroo\Status::SUCCESS) {
        $order = $transactionStatusResponse->getInvoiceNumber();
        $currency = $transactionStatusResponse->getCurrency();
        $amount = $transactionStatusResponse->getAmount();
        echo "The order {$order} with amount {$currency} {$amount} has been paid.";
    } elseif ($transactionStatusResponse->getStatus() === \Buckaroo\Status::PENDING_PROCESSING) {
        $paymentCode = $transactionStatusResponse->getPaymentCode();
        echo "The order is pending. Fetch transaction details later for order with payment code {$paymentCode}.";
    } else {
        echo 'Order has not been paid for.';
    }
} catch (\Buckaroo\Exceptions\InvalidSignatureException $e) {
    echo 'Signature does not match, possible break in attempt.';
}
```