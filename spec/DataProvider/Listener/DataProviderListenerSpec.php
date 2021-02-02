<?php declare(strict_types=1);

namespace spec\Kiboko\Component\PHPSpecExtension\DataProvider\Listener;

use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvidedExampleNode;
use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvider;
use Kiboko\Component\PHPSpecExtension\DataProvider\Listener\DataProviderListener;
use PhpSpec\Event\SpecificationEvent;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\ObjectBehavior;
use spec\Kiboko\Component\PHPSpecExtension\DataProvider\SpecExample;

final class DataProviderListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new DataProvider());
        $this->beAnInstanceOf(DataProviderListener::class);
    }

    function it_is_adding_examples_to_specification(SpecificationNode $specification, SpecificationEvent $event)
    {
        $this->beConstructedWith(new DataProvider());

        $event->getSpecification()->willReturn($specification);

        $reflection = new \ReflectionClass(SpecExample::class);

        $specification->getExamples()->willReturn([
            $example = new ExampleNode(
                'Example method with an array provider',
                $reflection->getMethod('example_method_with_an_array_provider')
            )
        ]);

        $specification->addExample(
            new DataProvidedExampleNode(
                '  <value>array_provider: #0</value> Example method with an array provider',
                $example,
                ['array_data', 1]
            )
        )->shouldBeCalledOnce();

        $specification->addExample(
            new DataProvidedExampleNode(
                '  <value>array_provider: #1</value> Example method with an array provider',
                $example,
                ['array_data', 2]
            )
        )->shouldBeCalledOnce();

        $this->beforeSpecification($event);
    }
}
