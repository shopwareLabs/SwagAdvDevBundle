<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Tests;

class ReflectionHelper
{
    /**
     * @param string $class
     * @param string $method
     *
     * @return \ReflectionMethod
     */
    public static function getMethod($class, $method)
    {
        $reflectionClass = new \ReflectionClass($class);
        $method = $reflectionClass->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param string $class
     * @param string $property
     *
     * @return \ReflectionProperty
     */
    public static function getProperty($class, $property)
    {
        $reflectionClass = new \ReflectionClass($class);
        $property = $reflectionClass->getProperty($property);
        $property->setAccessible(true);

        return $property;
    }
}
