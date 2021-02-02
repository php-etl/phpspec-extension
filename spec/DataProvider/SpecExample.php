<?php declare(strict_types=1);

namespace spec\Kiboko\Component\PHPSpecExtension\DataProvider;

use PhpSpec\ObjectBehavior;

final class SpecExample extends ObjectBehavior
{
    function example_method_with_no_phpdoc(string $code, int $index)
    {
    }

    /**
     *
     */
    function example_method_with_no_provider_declared(string $code, int $index)
    {
    }

    /**
     * @dataProvider not_iterable_provider
     */
    function example_method_with_a_not_iterable_provider(string $code, int $index)
    {
    }

    /**
     * @dataProvider erroneous_provider
     */
    function example_method_with_an_erroneous_provider(string $code, int $index)
    {
    }

    /**
     * @dataProvider empty_provider
     */
    function example_method_with_an_empty_provider(string $code, int $index)
    {
    }

    /**
     * @dataProvider array_provider
     */
    function example_method_with_an_array_provider(string $code, int $index)
    {
    }

    /**
     * @dataProvider generator_provider
     */
    function example_method_with_a_generator_provider(string $code, int $index)
    {
    }

    /**
     * @dataProvider iterator_provider
     */
    function example_method_with_an_iterator_provider(string $code, int $index)
    {
    }

    /**
     * @dataProvider array_provider
     * @dataProvider generator_provider
     * @dataProvider iterator_provider
     */
    function example_method_with_several_providers(string $code, int $index)
    {
    }

    /**
     * @dataProvider variadic_provider
     */
    function example_method_with_variadic_provider(string $code, int ...$index)
    {
    }

    function array_provider(): iterable
    {
        return [
            ['array_data', 1],
            ['array_data', 2],
        ];
    }

    function generator_provider(): iterable
    {
        yield ['generator_data', 1];
        yield ['generator_data', 2];
    }

    function iterator_provider(): iterable
    {
        return new \ArrayIterator([
            ['iterator_data', 1],
            ['iterator_data', 2],
        ]);
    }

    function variadic_provider(): iterable
    {
        return new \ArrayIterator([
            ['variadic_data', 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ['variadic_data', 2, 4, 6, 8],
        ]);
    }

    function empty_provider(): iterable
    {
        // \EmptyIterator does not provide \Countable interface
        return new \ArrayIterator();
    }

    function not_iterable_provider()
    {
        return new \stdClass();
    }
}
