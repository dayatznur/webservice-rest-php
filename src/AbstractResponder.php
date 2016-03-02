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

namespace FrancisDesjardins\WebService\Rest;

abstract class AbstractResponder implements ResponderInterface
{
    use DataAwareTrait;

    private $encoder;

    /**
     * Outputs the response
     *
     * @param ResponderEncoderInterface $encoder
     */
    final public function respond(ResponderEncoderInterface $encoder)
    {
        //! if the encoder has already been set
        //! do not override it
        if (!$this->encoder && !!$encoder) {
            $this->encoder = $encoder;
        }

        //! send an acceptable content-type to the client
        header($this->header());

        //! retrieve the response to send
        $response = $this->response();

        //! encode response
        if (!Utility::instance()->debug() && !ob_get_length() && !!$this->encoder) {
            $encodedResponse = $encoder->encode($response);

            if ($encodedResponse !== false) {
                $response = $encodedResponse;

                if ($encoder->getEncoding() !== false) {
                    header(sprintf('Content-Encoding: %1$s', $encoder->getEncoding()));
                }
            }
        }

        //! optional but could be useful
        header(sprintf('Content-Length: %1$s', ob_get_length() + strlen($response)));

        //! write the response to the client
        echo $response;
    }

    public function setEncoder(ResponderEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Returns the response to output to the client
     * @return string
     */
    abstract public function response();

    /**
     * Returns the content-type header string
     * @return string
     */
    abstract public function header();
}
