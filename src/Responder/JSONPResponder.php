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

use FrancisDesjardins\WebService\Rest\Event\MissingCallbackEvent;
use FrancisDesjardins\WebService\Rest\FatFreeFrameworkTrait;
use FrancisDesjardins\WebService\Rest\SystemEventEmitterTrait;
use FrancisDesjardins\WebService\Rest\Utility;

class JSONPResponder extends JSONResponder
{
    use FatFreeFrameworkTrait;
    use SystemEventEmitterTrait;

    const CONTENT_TYPE = 'application/javascript';
    const RESPONSE_TEMPLATE = '%1$s(%2$s)';

    public function header()
    {
        return sprintf(self::HEADER_CONTENT_TYPE_TEMPLATE, self::CONTENT_TYPE, Utility::instance()->encoding());
    }

    public function response()
    {
        $callback = self::callback();

        //! the callback was not provided by the client
        if (!$callback) {
            $this->dispatchEvent(new MissingCallbackEvent());
        }

        return sprintf(self::RESPONSE_TEMPLATE, $callback, parent::response());
    }

    final public static function callback()
    {
        $fff = self::fff();

        return $fff->get(
            sprintf(
                '%1$s.%2$s',
                $fff->get('VERB') == 'POST' ? 'POST' : 'GET',
                $fff->get('jsonp.callback')
            )
        );
    }
}
