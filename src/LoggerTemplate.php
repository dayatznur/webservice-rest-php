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

class LoggerTemplate
{
    const LOGGER_MESSAGE_ERROR = '{text}';
    const LOGGER_MESSAGE_INVALID_CLASS_INSTANCE = 'Class {%1$s} is not an instance of class {%2$s}';
    const LOGGER_MESSAGE_INVALID_PARAM = 'Parameter {%1$s} in method {%2$s} of class {%3$s} is invalid';
    const LOGGER_MESSAGE_FUNCTION_NOT_IMPLEMENTED = 'Function {%1$s} not implemented in class {%2$s}';
    const LOGGER_MESSAGE_JSONP_MISSING_CALLBACK = 'JSONP callback [{callback}] is missing from [verb] [uri]';
    const LOGGER_MESSAGE_VERB_NOT_ALLOWED = '{verb} not allowed for [{pattern}]';
    const LOGGER_MESSAGE_REQUEST_INFO = 'REQUEST : [{verb}] pattern [{pattern}] for [{uri}] with [{body}]';
    const LOGGER_MESSAGE_RESPONSE_INFO = 'RESPONSE : [{verb}] pattern [{pattern}] for [{uri}] with [{response}]';
    const LOGGER_MESSAGE_SECURITY_FAILURE = 'SECURITY : [{security}] failed for [{pattern}]';
    const LOGGER_MESSAGE_STORAGE_ERROR = 'There was a problem with the storage #%1$s : %2$s';
    const LOGGER_MESSAGE_MODEL_MERGE_PROPERTY = 'Property {%1$s} of {%2$s} already exists in {%2$s}; cannot merge';
}
