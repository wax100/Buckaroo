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
namespace Buckaroo\ServiceOperationResponse;

use Buckaroo\Exceptions\MissingFieldException;

/**
 * 
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @date 15-6-2016
 */
class ServiceOperationResponse
{
    /**
     * Key value pairs with 
     * @var array
     */
    protected $fields = [];

    /**
     * Map the raw format of a request result.
     * @param string $buckarooResponse
     */
    public function __construct($buckarooResponse)
    {
        $parts = explode('&', trim($buckarooResponse));
        foreach ($parts as $part) {
            $keyValue = explode('=', $part);
            if (count($keyValue) === 2) {
                $key = urldecode($keyValue[0]);
                $value = urldecode($keyValue[1]);

                $this->fields[$key] = $value;
            }
        }
    }

    /**
     * 
     * @param string $key
     * @return string
     * @throws MissingFieldException
     */
    public function getField($key)
    {
        if (!isset($this->fields[$key])) {
            throw new MissingFieldException;
        }

        return $this->fields[$key];
    }

    /**
     * Get a list of all the fields.
     * @return array
     */
    public function getFieldList()
    {
        return array_keys($this->fields);
    }
}
