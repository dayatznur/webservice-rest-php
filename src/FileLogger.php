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

use Exception;
use Log;
use Psr\Log\AbstractLogger;
use Web;

class FileLogger extends AbstractLogger
{
    const LOGGER_DATE_FORMAT = '';
    const LOGGER_FILENAME = 'log';
    const LOGGER_FILENAME_DATE_FORMAT = 'Y-m-d';

    private $logger;

    public function __construct()
    {
        $this->logger = new Log(self::filename());
    }

    public static function filename()
    {
        return sprintf(
            '%1$s-%2$s',
            self::LOGGER_FILENAME,
            Web::instance()->slug(Utility::date(self::LOGGER_FILENAME_DATE_FORMAT))
        );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $message = $this->interporate($message, $context);

        try {
            $this->logger->write(sprintf('[%1$s] %2$s', mb_strtoupper($level), $message), 'Y-m-d H:i:s');
        } catch (Exception $ex) {
            /** @TODO Do something if you get an I/O error */
        }
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param $message
     * @param array $context
     * @return string
     */
    public function interporate($message, array $context = [])
    {
        //! build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        //! interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
