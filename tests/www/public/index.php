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

ini_set('date.timezone', 'UTC');

ini_set('xdebug.var_display_max_depth', 10);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

require_once '../../../vendor/autoload.php';

use FrancisDesjardins\WebService\Rest\Responder\Encoder\NoopResponderEncoder;
use FrancisDesjardins\WebService\Rest\Responder\ErrorResponder;
use FrancisDesjardins\WebService\Rest\Utility;

/** @var Base $fw */
$fw = Base::instance();

//! load globals
$fw->config('../app/globals.ini');

//! load globals (OS specific)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $fw->config('../app/globals.windows.ini');
} else {
    $fw->config('../app/globals.linux.ini');
}

//! load custom sections
$fw->config('../app/custom.ini');

//! load mappings
$fw->config('../app/maps.ini');

//! global error handler
if (!Utility::instance()->debug()) {
    $fw->set('ONERROR', function (Base $fw) {
        Utility::instance()->flushOutputBuffer();

        $responder = new ErrorResponder($fw);

        $responder->respond(new NoopResponderEncoder());
    });
}

//! run it!
$fw->run();
