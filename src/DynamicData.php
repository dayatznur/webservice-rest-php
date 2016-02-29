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

class DynamicData implements DataInterface
{
    use DynamicDataMemberAwareTrait;

    /**
     * @param array $members
     */
    public function __construct(array $members = [])
    {
        if (!empty($members)) {
            $this->setMembers($members);
        }
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        return Utility::instance()->jsonEncode($this->serialize());
    }

    //! PUBLIC

    /**
     * @param DataInterface $data
     * @param bool $overwrite
     * @return $this
     * @throws MemberAccessException
     */
    public function merge(DataInterface $data, $overwrite = false)
    {
        //! get host members
        $hostMembers = $this->getMembers();

        //! get guest members
        $guestMembers = $data->getMembers();

        //! this iterates over the guest members and checks
        //! if the member does not already exists on the host
        foreach ($guestMembers as $member => $value) {
            if (!array_key_exists($member, $hostMembers) || $overwrite) {
                //! merge the guest member to the host
                $this->members[$member] = $value;
            } else { //! the member already exist; crash :)
                throw new MemberAccessException(
                    sprintf(
                        MemberAccessException::PROPERTY_ALREADY_EXISTS,
                        $member,
                        get_class($this),
                        get_class($data)
                    )
                );
            }
        }

        //! chaining
        return $this;
    }

    /**
     * Mutates the host into something else...
     * @param DataTransformerInterface $transformer
     * @return mixed
     */
    public function transform(DataTransformerInterface $transformer)
    {
        return $transformer->transform($this);
    }

    /**
     * @return array
     */
    final public function serialize()
    {
        return $this->serializeRecursive($this->getMembers());
    }

    //! PRIVATE

    /**
     * @param mixed $data
     * @return mixed array
     */
    private function serializeRecursive($data)
    {
        //! no need to serialize these types; just return it
        if (is_null($data) || is_scalar($data)) {
            return $data;
        }

        //! the 'data' is an array, a resource or an object
        //! we will serialize it into an array
        $serializedData = array();

        //! indexed array ?
        if ($data === array_values((array)$data)) {
            foreach ($data as $value) {
                $serializedData[] = $this->serializeRecursive($value);
            }

            return $serializedData;
        } else { //! associative array, object or resource
            if ($data instanceof DataInterface) {
                return $data->serialize();
            } else {
                foreach ($data as $key => $value) {
                    $serializedData[$key] = $this->serializeRecursive($value);
                }

                return $serializedData;
            }
        }
    }
}
