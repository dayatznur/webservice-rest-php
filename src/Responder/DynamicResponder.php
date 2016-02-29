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

use FrancisDesjardins\WebService\Rest\AbstractResponder;
use Web;

class DynamicResponder extends AbstractResponder
{
    /** @var false|string  */
    protected $acceptable;

    public function __construct()
    {
        $this->acceptable = Web::instance()->acceptable([
            HtmlResponder::CONTENT_TYPE,
            TextResponder::CONTENT_TYPE,
            JSONResponder::CONTENT_TYPE,
            JSONPResponder::CONTENT_TYPE
        ]);

        switch ($this->acceptable) {
            case JSONPResponder::CONTENT_TYPE:
                $this->responder = new JSONPResponder();
                break;
            case JSONResponder::CONTENT_TYPE:
                $this->responder = new JSONResponder();
                break;
            case HtmlResponder::CONTENT_TYPE:
                $this->responder = new HtmlResponder();
                break;
            case TextResponder::CONTENT_TYPE:
                $this->responder = new TextResponder();
                break;
            default:
                $this->responder = new TextResponder();
        }
    }

    /**
     * Returns the response to output to the client
     * @return string
     */
    public function response()
    {
        $data = $this->getData();

        if (!is_null($data)) {
            $this->responder->setData($data);
        }

        return $this->responder->response();
    }

    /**
     * Returns the content-type header string
     * @return string
     */
    public function header()
    {
        return $this->responder->header();
    }
}
