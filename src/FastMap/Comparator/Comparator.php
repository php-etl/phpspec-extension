<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\FastMap\Comparator;

final class Comparator
{
    public static function isEqual($left, $right): bool
    {
        if (is_object($left) && is_object($right)) {
            return self::isObjectsEqual($left, $right);
        }

        if (gettype($left) !== gettype($right)) {
            return false;
        }

        return $left === $right;
    }

    public static function isNotEqual($left, $right): bool
    {
        if (is_object($left) && is_object($right)) {
            return self::isObjectsNotEqual($left, $right);
        }

        if (gettype($left) !== gettype($right)) {
            return true;
        }

        return $left !== $right;
    }

    private static function isObjectsEqual(object $left, object $right): bool
    {
        $reflectionLeft = new \ReflectionObject($left);
        $reflectionRight = new \ReflectionObject($right);

        if ($reflectionLeft->getName() !== $reflectionRight->getName()) {
            return false;
        }

        foreach ($reflectionLeft->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isInitialized($left) && !$property->isInitialized($right)) {
                continue;
            }

            if (!$property->isInitialized($left) || !$property->isInitialized($right)) {
                return false;
            }

            if (!self::isEqual($property->getValue($left), $property->getValue($right))) {
                return false;
            }
        }

        return true;
    }

    private static function isObjectsNotEqual(object $left, object $right): bool
    {
        $reflectionLeft = new \ReflectionObject($left);
        $reflectionRight = new \ReflectionObject($right);

        if ($reflectionLeft->getName() === $reflectionRight->getName()) {
            return true;
        }

        foreach ($reflectionLeft->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isInitialized($left) && !$property->isInitialized($right)) {
                continue;
            }

            if (!$property->isInitialized($left) || !$property->isInitialized($right)) {
                return true;
            }

            if (!self::isNotEqual($property->getValue($left), $property->getValue($right))) {
                return true;
            }
        }

        return false;
    }
}
