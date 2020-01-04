<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\DataProvider\Runner\Maintainer;

use Kiboko\Component\ETL\PHPSpecExtension\DataProvider\DataProvidedExampleNode;
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
        foreach ($example->getFunctionReflection()->getParameters() as $position => $parameter) {
            if (!array_key_exists($position, $arguments)) {
                continue;
            }

            $collaborators->set($parameter->getName(), $arguments[$position]);
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