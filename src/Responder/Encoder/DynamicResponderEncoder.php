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

namespace FrancisDesjardins\WebService\Rest\Responder\Encoder;

use FrancisDesjardins\WebService\Rest\AbstractResponderEncoder;
use FrancisDesjardins\WebService\Rest\ResponderEncoderInterface;

class DynamicResponderEncoder extends AbstractResponderEncoder
{
    /** @var ResponderEncoderInterface */
    private $encoder;

    /**
     * @inheritDoc
     */
    public function encode($data)
    {
        $acceptEncoding = self::fff()->get('SERVER')['HTTP_ACCEPT_ENCODING'];

        foreach (explode(',', str_replace(' ', '', $acceptEncoding)) as $encoding) {
            switch ($encoding) {
                case GzipResponderEncoder::ENCODING:
                    $this->encoder = new GzipResponderEncoder();
                    break;
                case DeflateResponderEncoder::ENCODING:
                    $this->encoder = new DeflateResponderEncoder();
                    break;
            }

            if (!!$this->encoder) {
                break;
            }
        }

        if (!$this->encoder) {
            $this->encoder = self::getDefaultEncoder();
        }

        return $this->encoder->encode($data);
    }

    /**
     * @inheritDoc
     */
    public function getEncoding()
    {
        if (!$this->encoder) {
            return false;
        }

        return $this->encoder->getEncoding();
    }
}
