<?php declare(strict_types=1);

namespace spec\Kiboko\Component\PHPSpecExtension\DataProvider\Runner\Maintainer;

use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvidedExampleNode;
use Kiboko\Component\PHPSpecExtension\DataProvider\Runner\Maintainer\DataProviderMaintainer;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Specification;
use spec\Kiboko\Component\PHPSpecExtension\DataProvider\SpecExample;

final class DataProviderMaintainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldBeAnInstanceOf(DataProviderMaintainer::class);
    }

    function it_is_configuring_indexed_collaborators(
        Specification $context,
        MatcherManager $matcherManager,
        CollaboratorManager $collaboratorManager
    ) {
        $parent = new ExampleNode('Lorem Ipsum', new \ReflectionMethod(SpecExample::class, 'example_method_with_an_array_provider'));
        $example = new DataProvidedExampleNode('array_provider: <value>#0</value> Lorem Ipsum', $parent, ['array_data', 1]);

        $collaboratorManager->set('code', 'array_data')->shouldBeCalledOnce();
        $collaboratorManager->set('index', 1)->shouldBeCalledOnce();

        $this->prepare($example, $context, $matcherManager, $collaboratorManager);
    }

    function it_is_configuring_mapped_collaborators(
        Specification $context,
        MatcherManager $matcherManager,
        CollaboratorManager $collaboratorManager
    ) {
        $parent = new ExampleNode('Lorem Ipsum', new \ReflectionMethod(SpecExample::class, 'example_method_with_an_array_provider'));
        $example = new DataProvidedExampleNode('array_provider: <value>#0</value> Lorem Ipsum', $parent, ['code' => 'array_data', 'index' => 1]);

        $collaboratorManager->set('code', 'array_data')->shouldBeCalledOnce();
        $collaboratorManager->set('index', 1)->shouldBeCalledOnce();

        $this->prepare($example, $context, $matcherManager, $collaboratorManager);
    }

    function it_is_configuring_variadic_collaborators(
        Specification $context,
        MatcherManager $matcherManager,
        CollaboratorManager $collaboratorManager
    ) {
        $parent = new ExampleNode('Lorem Ipsum', new \ReflectionMethod(SpecExample::class, 'example_method_with_variadic_provider'));
        $example = new DataProvidedExampleNode('array_provider: <value>#0</value> Lorem Ipsum', $parent, ['variadic_data', 1, 2, 3, 4, 5, 6]);

//        $collaboratorManager->set('code', 'variadic_data')->shouldBeCalledOnce();
//        $collaboratorManager->set('index', 1)->shouldBeCalledOnce();
//        $collaboratorManager->set('index', 2)->shouldBeCalledOnce();
//        $collaboratorManager->set('index', 3)->shouldBeCalledOnce();
//        $collaboratorManager->set('index', 4)->shouldBeCalledOnce();
//        $collaboratorManager->set('index', 5)->shouldBeCalledOnce();
//        $collaboratorManager->set('index', 6)->shouldBeCalledOnce();
//
//        $this->prepare($example, $context, $matcherManager, $collaboratorManager);

        $this->shouldThrow(
            new \LogicException('Variadic arguments is not supported by PHPSpec')
        )->during('prepare', [$example, $context, $matcherManager, $collaboratorManager]);
    }
}
