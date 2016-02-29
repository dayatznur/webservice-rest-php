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

use FrancisDesjardins\WebService\Rest\LoggerLevelEnum;
use League\Event\AbstractEvent;

class LogEvent extends AbstractEvent
{
    const NAME = 'system.log';

    protected $level = 'info';
    protected $message = '';
    protected $context = [];

    /**
     * @param LoggerLevelEnum $level
     * @param string $message
     * @param array $context
     */
    public function __construct(LoggerLevelEnum $level, $message = '', array $context = [])
    {
        $this->setLevel($level);
        $this->setMessage($message);
        $this->setContext($context);
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function setContext(array $context = [])
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param LoggerLevelEnum $level
     * @return $this
     */
    public function setLevel(LoggerLevelEnum $level)
    {
        $this->level = (string)$level->getValue();

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = (string)$message;

        return $this;
    }
}
