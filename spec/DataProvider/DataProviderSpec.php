<?php declare(strict_types=1);

namespace spec\Kiboko\Component\PHPSpecExtension\DataProvider;

use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvider;
use Kiboko\Component\PHPSpecExtension\DataProvider\InvalidDataProvider;
use Kiboko\Component\PHPSpecExtension\DataProvider\NoDataProviderAvailable;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Locator\Resource;
use PhpSpec\ObjectBehavior;

final class DataProviderSpec extends ObjectBehavior
{
    function it_is_intializable()
    {
        $this->beAnInstanceOf(DataProvider::class);
    }

    function it_excludes_when_no_phpdoc(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds no data provider',
            $reflection->getMethod('example_method_with_no_phpdoc')
        );

        $this->walk($example)
            ->shouldThrow(new NoDataProviderAvailable(
            'The specified example for PHPSpec Data Provider does not have a PHPDoc.'
            ))
            ->duringCurrent();
    }

    function it_excludes_erroneous_data_providers(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds no data provider',
            $reflection->getMethod('example_method_with_an_erroneous_provider')
        );

        $this->walk($example)
            ->shouldThrow(new InvalidDataProvider(
                'The specified Data Provider method in the PHPSpec example does not exist.'
            ))
            ->duringCurrent();
    }

    function it_finds_no_data_provider_declared(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds no data provider',
            $reflection->getMethod('example_method_with_no_provider_declared')
        );

        $this->walk($example)
            ->shouldThrow(new NoDataProviderAvailable(
                'The specified example for PHPSpec Data Provider does not have a Data Provider declared.'
            ))
            ->duringCurrent();
    }

    function it_finds_an_empty_data_provider(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds an empty data provider',
            $reflection->getMethod('example_method_with_an_empty_provider')
        );

        $this->walk($example)
            ->shouldThrow(new InvalidDataProvider(
                'The specified function for PHPSpec Data Provider does not provide data to test against.'
            ))
            ->duringCurrent();
    }

    function it_finds_a_not_iterable_data_provider(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds not iterable data provider',
            $reflection->getMethod('example_method_with_a_not_iterable_provider')
        );

        $this->walk($example)
            ->shouldThrow(new InvalidDataProvider(
                'The specified function for PHPSpec Data Provider does not provide iterable data to test against.'
            ))
            ->duringCurrent();
    }

    function it_finds_one_array_data_provider(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds one array data provider',
            $reflection->getMethod('example_method_with_an_array_provider')
        );

        $this->walk($example)
            ->shouldIterateLike((function(){
                yield 'array_provider: #0' => ['array_data', 1];
                yield 'array_provider: #1' => ['array_data', 2];
            })());
    }

    function it_finds_one_generator_data_provider(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds one \\Genrator data provider',
            $reflection->getMethod('example_method_with_a_generator_provider')
        );

        $this->walk($example)
            ->shouldIterateLike((function(){
                yield 'generator_provider: #0' => ['generator_data', 1];
                yield 'generator_provider: #1' => ['generator_data', 2];
            })());
    }

    function it_finds_one_iterator_data_provider(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds one \\Iterator data provider',
            $reflection->getMethod('example_method_with_an_iterator_provider')
        );

        $this->walk($example)
            ->shouldIterateLike((function(){
                yield 'iterator_provider: #0' => ['iterator_data', 1];
                yield 'iterator_provider: #1' => ['iterator_data', 2];
            })());
    }

    function it_finds_several_data_providers(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds several data providers',
            $reflection->getMethod('example_method_with_several_providers')
        );

        $this->walk($example)
            ->shouldIterateLike((function(){
                yield 'array_provider: #0' => ['array_data', 1];
                yield 'array_provider: #1' => ['array_data', 2];
                yield 'generator_provider: #0' => ['generator_data', 1];
                yield 'generator_provider: #1' => ['generator_data', 2];
                yield 'iterator_provider: #0' => ['iterator_data', 1];
                yield 'iterator_provider: #1' => ['iterator_data', 2];
            })());
    }

    function it_finds_variadic_data_provider(Resource $resource)
    {
        $reflection = new \ReflectionClass(SpecExample::class);

        $example = new ExampleNode(
            'It finds variadic data providers',
            $reflection->getMethod('example_method_with_variadic_provider')
        );

        $this->walk($example)
            ->shouldIterateLike((function(){
                yield 'variadic_provider: #0' => ['variadic_data', 1, 2, 3, 4, 5, 6, 7, 8, 9];
                yield 'variadic_provider: #1' => ['variadic_data', 2, 4, 6, 8];
            })());
    }
}
