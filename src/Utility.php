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

use Prefab;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Web;

class Utility extends Prefab
{
    use FatFreeFrameworkTrait;

    const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

    const PHP_CR = "\r";
    const PHP_CRLF = "\r\n";
    const PHP_LF = "\n";

    /**
     * Return the defined logger will use 'NullLogger' is none or defined
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        $fff = self::fff();

        $className = $fff->get('logger.class');

        return class_exists($className) ? new $className : new NullLogger();
    }

    /**
     * Converts unicode codepoint to UTF-8
     * @param string $text
     * @return string
     */
    public function convertUnicodeToUTF8($text)
    {
        return mb_convert_encoding(preg_replace('/U\+([0-9A-F]*)/', '&#x\\1;', $text), 'UTF-8', 'HTML-ENTITIES');
    }

    /**
     * Recursively clear existing output buffer
     */
    public function flushOutputBuffer()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        ob_start();
    }

    public function jsonpCallback()
    {
        $fff = self::fff();

        return $fff->get(sprintf('%1$s.%2$s', $this->verb() == 'POST' ? 'POST' : 'GET', $fff->get('jsonp.callback')));
    }

    public function jsonEncode($value)
    {
        if (defined('JSON_UNESCAPED_UNICODE')) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return json_encode($value);
    }

    public function encoding()
    {
        $fff = self::fff();

        return $fff->get('ENCODING');
    }

    public function verb()
    {
        $fff = self::fff();

        return $fff->get('VERB');
    }

    public function uri()
    {
        $fff = self::fff();

        return $fff->get('URI');
    }

    /**
     * Uses "date" function to format an UTC timestamp into its local timezone counterpart
     *
     * @param string $format
     * @param int $timestamp UTC timestamp to format
     * @return string|null
     */
    public static function date($format = self::DEFAULT_DATE_FORMAT, $timestamp = 0)
    {
        $return_value = null;

        if (!is_null($timestamp)) {
            $timestamp = $timestamp == 0 ? strtotime(self::now(true)) : $timestamp;

            $return_value = date(
                $format,
                gmmktime(
                    date('H', $timestamp),
                    date('i', $timestamp),
                    date('s', $timestamp),
                    date('m', $timestamp),
                    date('d', $timestamp),
                    date('Y', $timestamp)
                )
            );
        }

        return $return_value;
    }

    /**
     * @return bool
     */
    public function debug()
    {
        return (bool)$this->debugLevel();
    }

    public function debugLevel()
    {
        $fff = self::fff();

        return (int)$fff->get('DEBUG') > 3 ? 3 : (int)$fff->get('DEBUG') < 0 ? 0 : (int)$fff->get('DEBUG');
    }

    /**
     * Returns the current date and time in SQL format : 1900-01-01 16:00:00
     *
     * @param bool $utc Retrieves UTC date and time
     * @return string
     */
    public static function now($utc = false)
    {
        if ($utc) {
            return gmdate(self::DEFAULT_DATE_FORMAT);
        }

        return date(self::DEFAULT_DATE_FORMAT);
    }
}
