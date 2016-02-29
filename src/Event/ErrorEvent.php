<?php
/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2014-2016 Francis Desjardins
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

namespace FrancisDesjardins\WebService\Rest\Event;

use FrancisDesjardins\WebService\Rest\DataInterface;
use FrancisDesjardins\WebService\Rest\DynamicData;
use FrancisDesjardins\WebService\Rest\ErrorEnum;
use League\Event\AbstractEvent;

class ErrorEvent extends AbstractEvent
{
    const
        ERROR401 = 401,
        ERROR403 = 403,
        ERROR404 = 404,
        ERROR405 = 405,
        ERROR409 = 409,
        ERROR500 = 500,
        NAME = 'system.error';

    /** @var int */
    protected $code;

    /** @var int */
    protected $codeDetail = 0;

    /**
     * @param ErrorEnum $code
     * @param int $codeDetail
     */
    public function __construct(ErrorEnum $code, $codeDetail = 0)
    {
        $this->setCode($code);
        $this->setCodeDetail($codeDetail);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getCodeDetail()
    {
        return $this->codeDetail;
    }

    /**
     * @return DataInterface
     */
    public function getMessage()
    {
        $error = new DynamicData();

        $error->error = $this->codeDetail != 0 ? $this->codeDetail : $this->code;

        return $error;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param ErrorEnum $code
     * @return $this
     */
    public function setCode(ErrorEnum $code)
    {
        $this->code = (int)$code->getValue();

        return $this;
    }

    /**
     * @param int $codeDetail
     * @return $this
     */
    public function setCodeDetail($codeDetail)
    {
        $this->codeDetail = !!$codeDetail ? (int)$codeDetail : 0;

        return $this;
    }
}
