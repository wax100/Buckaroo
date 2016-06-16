<?php
/*
 * The MIT License
 *
 * Copyright 2014 Soneritics Webdevelopment.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Buckaroo\ServiceOperations;

use Buckaroo\Currency\ICurrency;
use Buckaroo\Exceptions\MissingPropertyException;
use Buckaroo\PaymentMethods\IPaymentMethod;

/**
 * 
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @date 15-6-2016
 */
class TransactionRequest extends ServiceOperation
{
    /**
     * @var IPaymentMethod
     */
    protected $paymentMethod;

    /**
     * @var ICurrency
     */
    protected $currency;

    /**
     * @var double
     */
    protected $amount;

    /**
     * @var string
     */
    protected $invoiceNumber;

    /**
     * @var string
     */
    protected $orderNumber;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $returnURL;

    /**
     * @var string
     */
    protected $cancelURL;

    /**
     * @var string
     */
    protected $rejectURL;

    /**
     * @var string
     */
    protected $errorURL;

    /**
     * Get the post fields for the requests.
     * @return array
     */
    public function getPostFields()
    {
        $this->validate();
        $mappedFields = $this->getMappedFields();
        $this->paymentMethod->addAdditionalFields($mappedFields);
        return $mappedFields;
    }

    /**
     * 
     * @param IPaymentMethod $paymentMethod
     * @return TransactionRequest
     */
    public function setPaymentMethod(IPaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * 
     * @param \Buckaroo\ServiceOperations\ICurrency $currency
     * @return TransactionRequest
     */
    public function setCurrency(ICurrency $currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * 
     * @param double $amount
     * @return TransactionRequest
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * 
     * @param string $invoiceNumber
     * @return TransactionRequest
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    /**
     * 
     * @param string $orderNumber
     * @return TransactionRequest
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    /**
     * 
     * @param string $description
     * @return TransactionRequest
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * 
     * @param string $returnURL
     * @return TransactionRequest
     */
    public function setReturnURL($returnURL)
    {
        $this->returnURL = $returnURL;
        return $this;
    }

    /**
     * 
     * @param string $cancelURL
     * @return TransactionRequest
     */
    public function setCancelURL($cancelURL)
    {
        $this->cancelURL = $cancelURL;
        return $this;
    }

    /**
     * 
     * @param string $rejectURL
     * @return TransactionRequest
     */
    public function setRejectURL($rejectURL)
    {
        $this->rejectURL = $rejectURL;
        return $this;
    }

    /**
     * 
     * @param string $errorURL
     * @return TransactionRequest
     */
    public function setErrorURL($errorURL)
    {
        $this->errorURL = $errorURL;
        return $this;
    }

    /**
     * Validate the properties.
     * @throws MissingPropertyException
     */
    protected function validate()
    {
        $mandatory = [
            'paymentMethod',
            'currency',
            'amount',
            'invoiceNumber',
            'websiteKey'
        ];

        foreach ($mandatory as $property) {
            if (empty($this->$property)) {
                throw new MissingPropertyException(
                    "Missing property: {$property}"
                );
            }
        }
    }

    /**
     * Get an array with key/value pairs for the mapped Buckaroo fields.
     * @return array
     */
    protected function getMappedFields()
    {
        $mapping = [
            'brq_payment_method' => $this->paymentMethod->getPaymentMethod(),
            'brq_websitekey' => $this->websiteKey,
            'brq_currency' => $this->currency->getCurrencyString(),
            'brq_amount' => $this->amount,
            'brq_invoicenumber' => $this->invoiceNumber,
            'brq_ordernumber' => $this->orderNumber,
            'brq_description' => $this->description,
            'brq_return' => $this->returnURL,
            'brq_returncancel' => $this->cancelURL,
            'brq_returnerror' => $this->rejectURL,
            'brq_returnreject' => $this->errorURL
        ];

        $result = [];
        foreach ($mapping as $key => $value) {
            if (!empty($value)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
