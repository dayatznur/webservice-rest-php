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

use FrancisDesjardins\WebService\Rest\Event\AfterRouteEvent;
use FrancisDesjardins\WebService\Rest\Event\BeforeRouteEvent;
use FrancisDesjardins\WebService\Rest\Event\ErrorEvent;
use FrancisDesjardins\WebService\Rest\Event\VerbNotAllowedEvent;
use FrancisDesjardins\WebService\Rest\Responder\Encoder\NoopResponderEncoder;

abstract class AbstractRest implements RestInterface
{
    use DataAwareTrait;
    use ResponderAwareTrait;
    use SystemEventEmitterTrait;

    const HOOK_BEFORE = 'before';
    const HOOK_AFTER = 'after';

    /**
     * @var ResponderEncoderInterface
     */
    private $encoder;

    /**
     * @inheritdoc
     */
    final public function afterRoute()
    {
        //! child 'after'
        if (method_exists($this, self::HOOK_AFTER)) {
            call_user_func([$this, self::HOOK_AFTER]);
        }

        //! emit a response
        $this->respond();

        //! trigger
        self::dispatchEvent(new AfterRouteEvent());
    }

    /**
     * @inheritdoc
     */
    final public function beforeRoute()
    {
        //! trigger
        $this->dispatchEvent(new BeforeRouteEvent());

        //! child 'before'
        if (method_exists($this, self::HOOK_BEFORE)) {
            call_user_func([$this, self::HOOK_BEFORE]);
        }

        //! check for security concerns
        Security::instance()->secure();
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        self::dispatchEvent(new VerbNotAllowedEvent());
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        self::dispatchEvent(new VerbNotAllowedEvent());
    }

    /**
     * @inheritdoc
     */
    public function post()
    {
        self::dispatchEvent(new VerbNotAllowedEvent());
    }

    /**
     * @inheritdoc
     */
    public function put()
    {
        self::dispatchEvent(new VerbNotAllowedEvent());
    }

    /**
     * Recursively clear existing output buffer
     */
    private function flushOutputBuffer()
    {
        //! in production we flush any buffered output
        if (!Utility::instance()->debug()) {
            Utility::instance()->flushOutputBuffer();
        }
    }

    private function respond()
    {
        $data = $this->getData();

        if (!is_null($data)) {
            /** @var AbstractResponder $responder */
            $responder = !!$this->responder ? $this->responder : $this->getResponder();

            if ($responder instanceof ResponderInterface) {
                //! we set the rest serializable object to the responder
                $responder->setData($data);

                //! we flush output buffer; prevents messy outputs
                //! runs only in production environment which is defined
                //! by FatFreeFramework 'DEBUG' configuration property
                $this->flushOutputBuffer();

                //! emit the response
                $responder->respond($this->getEncoder());
            } else {
                /** todo Need to create own event */
                $this->dispatchEvent(new ErrorEvent(ErrorEnum::ERROR500()));
            }
        }
    }

    /**
     * Sets the encoder for the response
     *
     * @param ResponderEncoderInterface $encoder
     */
    final public function setEncoder(ResponderEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Returns the current response encoder
     *
     * @return ResponderEncoderInterface
     */
    final public function getEncoder()
    {
        if (!$this->encoder instanceof ResponderEncoderInterface) {
            $this->encoder = AbstractResponderEncoder::getDefaultEncoder();

            //! could not load the default encoder
            if ($this->encoder === false) {
                $this->encoder = new NoopResponderEncoder();
            }
        }

        return $this->encoder;
    }
}
