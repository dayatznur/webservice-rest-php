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

use ReflectionClass;
use ReflectionProperty;

trait DynamicDataMemberAwareTrait
{
    /** @var array Holds dynamically added members */
    private $members = [];

    /**
     * @param string $member
     * @return bool
     */
    final public function __isset($member)
    {
        return isset($this->members[$member]);
    }

    /**
     * @param string $member
     * @return mixed
     * @throws MemberAccessException
     */
    final public function __get($member)
    {
        //! building method to match the property
        $method = 'get' . ucfirst($member);

        //! is the method defined
        if (in_array($method, get_class_methods($this))) {
            //! yes; call the property's method
            return $this->$method();
        } else {
            //! no; return the dynamic property
            if (isset($this->members[$member])) {
                return $this->members[$member];
            } else {
                throw new MemberAccessException(
                    sprintf(
                        MemberAccessException::PROPERTY_DOES_NOT_EXIST,
                        $member,
                        get_class($this)
                    )
                );
            }
        }
    }

    /**
     * @param string $member
     * @param mixed $value
     * @return $this
     * @throws MemberAccessException
     */
    final public function __set($member, $value)
    {
        //! property already set; override then
        if (array_key_exists($member, $this->members)) {
            $this->members[$member] = $value;
        } else {
            //! building method to match the property
            $method = 'set' . ucfirst($member);

            //! is the method defined
            if (in_array($method, get_class_methods($this))) {
                //! if so, is the property defined
                if (property_exists($this, $member)) {
                    //! yes; call the property's method
                    is_array($value) ? call_user_func_array([$this, $method], $value) : $this->$method($value);
                } else {
                    //! no; generate an error (that shall not happen)
                    throw new MemberAccessException(
                        sprintf(
                            MemberAccessException::PROPERTY_OF_DOES_NOT_EXIST,
                            $member,
                            $method,
                            get_class($this)
                        )
                    );
                }
            } else {
                //! there is no method to match the property so set it dynamically
                $this->members[$member] = $value;
            }
        }

        //! chaining
        return $this;
    }

    /**
     * @param string $member
     */
    final public function __unset($member)
    {
        unset($this->members[$member]);
    }

    //! GET

    /**
     * @return array
     */
    final public function getMembers()
    {
        //! retrieve dynamic members
        $members = $this->members;

        //! retrieve defined members; if necessary
        if (get_class($this) != __CLASS__) {
            $reflection = new ReflectionClass($this);
            $reflectionMembers = $reflection->getProperties(
                ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
            );

            foreach ($reflectionMembers as $member) {
                $member->setAccessible(true);

                //! add 'child members' to 'dynamically added members'
                $members[$member->getName()] = $member->getValue($this);
            }
        }

        return $members;
    }

    //! SET

    /**
     * @param array $members
     * @return $this
     */
    public function setMembers(array $members = [])
    {
        foreach ($members as $member => $value) {
            $this->__set($member, $value);
        }

        //! chaining
        return $this;
    }

    //! PUBLIC

    final public function clear()
    {
        foreach (array_keys($this->getMembers()) as $member) {
            unset($this->$member);
        }
    }
}
