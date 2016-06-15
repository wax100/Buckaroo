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
namespace Buckaroo;

use Buckaroo\ServiceOperations\ServiceOperation;
use Buckaroo\ServiceOperationResponse\ServiceOperationResponse;
use Buckaroo\Gateways\IGateway;
use Buckaroo\Exceptions\UnableToConnectException;

/**
 * 
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @date 15-6-2016
 */
class Buckaroo
{
    /**
     * The gateway to use.
     * @var IGateway
     */
    protected $gateway;

    /**
     * Website key. Mandatory for any request made to the Buckaroo services.
     * @var string
     */
    protected $websiteKey;

    /**
     * Website key. Mandatory for any request made to the Buckaroo services.
     * @var string
     */
    protected $secretKey;

    /**
     * Constructor for the Buckaroo class.
     * @param IGateway $gateway
     * @param string $websiteKey
     * @param string $secretKey
     */
    public function __construct(IGateway $gateway, $websiteKey, $secretKey)
    {
        $this->gateway = $gateway;
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;
    }

    /**
     * 
     * @param ServiceOperation $serviceOperation
     * @return ServiceOperationResponse
     * @throws UnableToConnectException
     */
    public function performServiceOperation(ServiceOperation $serviceOperation)
    {
        $serviceOperation->setWebsiteKey($this->websiteKey);
        $postFields = $serviceOperation->getPostFields();
        ksort($postFields);
        $signature = new Signature($postFields, $this->secretKey);
        $postFields['brq_signature'] = (string)$signature;

        $rawResult = $this->execute($postFields);
        $result = new ServiceOperationResponse($rawResult);
        return $result;
    }

    /**
     * 
     * @param array $postFields
     * @return string
     * @throws UnableToConnectException
     */
    protected function execute(array $postFields)
    {
        $dataArray = [];
        foreach ($postFields as $key => $value) {
            $dataArray[] = sprintf('%s=%s', urlencode($key), urlencode($value));
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->gateway->getURL());
            curl_setopt($ch, CURLOPT_POST, count($dataArray));
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $dataArray));
            $result = curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $ex) {
            throw new UnableToConnectException($ex->getMessage());
        }

        return $result;
    }
}
