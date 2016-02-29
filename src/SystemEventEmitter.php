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
use FrancisDesjardins\WebService\Rest\Event\AfterRouteListener;
use FrancisDesjardins\WebService\Rest\Event\BeforeRouteEvent;
use FrancisDesjardins\WebService\Rest\Event\BeforeRouteListener;
use FrancisDesjardins\WebService\Rest\Event\ErrorEvent;
use FrancisDesjardins\WebService\Rest\Event\ErrorListener;
use FrancisDesjardins\WebService\Rest\Event\LogEvent;
use FrancisDesjardins\WebService\Rest\Event\LogListener;
use FrancisDesjardins\WebService\Rest\Event\MissingCallbackEvent;
use FrancisDesjardins\WebService\Rest\Event\MissingCallbackListener;
use FrancisDesjardins\WebService\Rest\Event\SecurityFailureEvent;
use FrancisDesjardins\WebService\Rest\Event\SecurityFailureListener;
use FrancisDesjardins\WebService\Rest\Event\VerbNotAllowedEvent;
use FrancisDesjardins\WebService\Rest\Event\VerbNotAllowedListener;
use League\Event\Emitter;
use Prefab;

class SystemEventEmitter extends Prefab
{
    private $emitter = null;

    /**
     * @return Emitter
     */
    public function emitter()
    {
        if (!$this->emitter) {
            $this->emitter = new Emitter();

            //! register system listeners
            $this->emitter->addListener(AfterRouteEvent::NAME, new AfterRouteListener());
            $this->emitter->addListener(BeforeRouteEvent::NAME, new BeforeRouteListener());
            $this->emitter->addListener(ErrorEvent::NAME, new ErrorListener());
            $this->emitter->addListener(LogEvent::NAME, new LogListener());
            $this->emitter->addListener(MissingCallbackEvent::NAME, new MissingCallbackListener());
            $this->emitter->addListener(SecurityFailureEvent::NAME, new SecurityFailureListener());
            $this->emitter->addListener(VerbNotAllowedEvent::NAME, new VerbNotAllowedListener());
        }

        return $this->emitter;
    }
}
