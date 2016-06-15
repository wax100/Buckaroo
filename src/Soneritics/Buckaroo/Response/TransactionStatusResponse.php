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
namespace Buckaroo\Response;

use Buckaroo\Exceptions\InvalidSignatureException;
use Buckaroo\Signature;

/**
 *
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @date 15-6-2016
 */
class TransactionStatusResponse
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * TransactionStatusResponse constructor.
     * @param array $data
     * @param string $secretKey
     */
    public function __construct(array $data, $secretKey)
    {
        $this->data = $data;
        $this->secretKey = $secretKey;
        $this->validateSignature();
    }

    /**
     * Get the status of an order.
     * @return int
     */
    public function getStatus()
    {
        return (int)$this->data['brq_statuscode'];
    }

    /**
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->data['brq_invoicenumber'];
    }

    /**
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->data['brq_currency'];
    }

    /**
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->data['brq_amount'];
    }

    /**
     *
     * @return string
     */
    public function getPaymentCode()
    {
        return $this->data['brq_payment'];
    }

    /**
     * Validate the transaction.
     * @throws InvalidSignatureException
     */
    protected function validateSignature()
    {
        $check = [];
        foreach ($this->data as $key => $value) {
            if ($key !== 'brq_signature') {
                $check[$key] = $value;
            }
        }

        $signature = new Signature($check, $this->secretKey);
        if ((string)$signature !== $this->data['brq_signature']) {
            throw new InvalidSignatureException;
        }
    }
}
