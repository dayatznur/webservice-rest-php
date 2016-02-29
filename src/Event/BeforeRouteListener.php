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

use FrancisDesjardins\WebService\Rest\FatFreeFrameworkTrait;
use FrancisDesjardins\WebService\Rest\LoggerLevelEnum;
use FrancisDesjardins\WebService\Rest\LoggerTemplate;
use FrancisDesjardins\WebService\Rest\SystemEventEmitterTrait;
use FrancisDesjardins\WebService\Rest\Utility;
use League\Event\AbstractListener;
use League\Event\EventInterface;

class BeforeRouteListener extends AbstractListener
{
    use FatFreeFrameworkTrait;
    use SystemEventEmitterTrait;

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        if ($event instanceof BeforeRouteEvent) {
            $fff = self::fff();

            self::dispatchEvent(
                new LogEvent(
                    LoggerLevelEnum::INFO(),
                    LoggerTemplate::LOGGER_MESSAGE_REQUEST_INFO,
                    [
                        'verb'=>$fff->get('VERB'),
                        'pattern'=>$fff->get('PATTERN'),
                        'uri'=>$fff->get('URI'),
                        'body'=>Utility::instance()->convertUnicodeToUTF8($fff->get('BODY'))
                    ]
                )
            );
        }
    }
}