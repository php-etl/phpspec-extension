<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\DataProvider;

use PhpSpec\Loader\Node\ExampleNode;

final class DataProvider
{
    // see https://www.php.net/manual/en/functions.user-defined.php
    private const PATTERN = '/@dataProvider\s+([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)/';

    public function supports(ExampleNode $example): bool
    {
        return false !== $example->getFunctionReflection()->getDocComment();
    }

    public function walk(ExampleNode $example): \Traversable
    {
        $functionReflection = $example->getFunctionReflection();
        if (false === ($comment = $functionReflection->getDocComment())) {
            throw new NoDataProviderAvailable(
                'The specified example for PHPSpec Data Provider does not have a PHPDoc.'
            );
        }

        if (!preg_match_all(self::PATTERN, $comment, $matches)) {
            throw new NoDataProviderAvailable(
                'The specified example for PHPSpec Data Provider does not have a Data Provider declared.'
            );
        }

        if ($functionReflection instanceof \ReflectionMethod) {
            yield from $this->wrapMethodDataProvider($functionReflection, ...$matches[1]);
        } elseif ($functionReflection instanceof \ReflectionFunction) {
            yield from $this->wrapFunctionDataProvider($functionReflection, ...$matches[1]);
        }
    }

    private function wrapMethodDataProvider(
        \ReflectionMethod $functionReflection,
        string ...$methods
    ): \Traversable {
        $classReflection = $functionReflection->getDeclaringClass();
        $object = $classReflection->newInstance();
        foreach ($methods as $method) {
            try {
                $dataProvider = $classReflection->getMethod($method)->invoke($object);
            } catch (\ReflectionException $exception) {
                throw new InvalidDataProvider(
                    'The specified Data Provider method in the PHPSpec example does not exist.',
                    0,
                    $exception
                );
            }

            if (!is_array($dataProvider) && !$dataProvider instanceof \Traversable) {
                throw new InvalidDataProvider(
                    'The specified function for PHPSpec Data Provider does not provide iterable data to test against.'
                );
            }

            if (is_countable($dataProvider) && count($dataProvider) <= 0) {
                throw new InvalidDataProvider(
                    'The specified function for PHPSpec Data Provider does not provide data to test against.'
                );
            }

            foreach ($dataProvider as $index => $value) {
                yield sprintf('%s: #%d', $method, $index) => $value;
            }
        }
    }

    private function wrapFunctionDataProvider(
        \ReflectionFunction $functionReflection,
        string ...$functions
    ): \Traversable {
        foreach ($functions as $function) {
            try {
                $dataProvider = (new \ReflectionFunction($function))->invoke();
            } catch (\ReflectionException $exception) {
                throw new InvalidDataProvider(
                    'The specified Data Provider method in the PHPSpec example does not exist.',
                    0,
                    $exception
                );
            }

            if (!is_iterable($dataProvider)) {
                throw new InvalidDataProvider(
                    'The specified function for PHPSpec Data Provider does not provide iterable data to test against.'
                );
            }

            if (is_countable($dataProvider) && count($dataProvider) <= 0) {
                throw new InvalidDataProvider(
                    'The specified function for PHPSpec Data Provider does provide an empty Data Provider.'
                );
            }

            foreach ($dataProvider as $index => $value) {
                yield sprintf('%s: #%d', $function, $index) => $value;
            }
        }
    }
}
