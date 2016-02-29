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

namespace FrancisDesjardins\WebService\Rest\Responder;

use FrancisDesjardins\WebService\Rest\FatFreeFrameworkTrait;
use FrancisDesjardins\WebService\Rest\ResponderEncoderInterface;
use FrancisDesjardins\WebService\Rest\ResponderInterface;
use Web;

class ErrorResponder implements ResponderInterface
{
    use FatFreeFrameworkTrait;

    protected $code;
    protected $message;
    protected $encoding;

    public function __construct()
    {
        $fff = self::fff();

        $this->encoding = $fff->get('ENCODING');
        $this->setCode($fff->get('ERROR.code'));
        $this->setMessage($fff->get('ERROR.text'));
    }

    public function respond(ResponderEncoderInterface $encoder)
    {
        header(
            sprintf(
                self::HEADER_CONTENT_TYPE_TEMPLATE,
                Web::instance()->acceptable(['text/plain', 'application/json', 'application/javascript']),
                $this->encoding
            )
        );

        echo !empty($this->message) ? $this->message : "{\"error\":$this->code}";
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
