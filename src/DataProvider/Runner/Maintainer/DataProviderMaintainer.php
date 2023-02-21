<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\DataProvider\Runner\Maintainer;

use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvidedExampleNode;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\Maintainer;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Specification;

final class DataProviderMaintainer implements Maintainer
{
    public function getPriority(): int
    {
        return 100;
    }

    public function supports(ExampleNode $example): bool
    {
        return $example instanceof DataProvidedExampleNode;
    }

    public function prepare(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ): void {
        if (!$example instanceof DataProvidedExampleNode) {
            return;
        }

        $arguments = $example->getProvidedData();
        $iterator = new \NoRewindIterator(new \ArrayIterator($example->getFunctionReflection()->getParameters()));
        foreach ($iterator as $position => $parameter) {
            if ($parameter->isVariadic()) {
                throw new \LogicException('Variadic arguments is not supported by PHPSpec');
            }

            if (array_key_exists($parameter->getName(), $arguments)) {
                $collaborators->set($parameter->getName(), $arguments[$parameter->getName()]);
                continue;
            }

            if (array_key_exists($position, $arguments)) {
                $collaborators->set($parameter->getName(), $arguments[$position]);
                continue;
            }
        }

        if ($iterator->valid()) {
            foreach (array_slice($arguments, $iterator->key()) as $value) {
                $collaborators->set($iterator->current()->getName(), $value);
            }
        }
    }

    public function teardown(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ): void {
    }
}
