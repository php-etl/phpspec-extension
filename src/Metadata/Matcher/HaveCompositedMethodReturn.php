<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\Metadata\IncompatibleTypeException;
use Kiboko\Contract\Metadata\IterableTypeMetadataInterface;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveCompositedMethodReturn extends BasicMatcher
{
    public function __construct(private readonly Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveCompositedMethodReturn' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        [$method] = $arguments;

        try {
            $typeDeclaration = $subject->getMethod($method)->getReturnType();
            if ($typeDeclaration instanceof IterableTypeMetadataInterface) {
                return true;
            }
        } catch (IncompatibleTypeException) {
            // Do nothing
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$method] = $arguments;

        return new FailureException(sprintf(
            'Expected the method %s to return a composite, but got %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($subject->getMethod($method)->getReturnType())
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$method] = $arguments;

        return new FailureException(sprintf(
            'Expected the method %s to not return a composite.',
            $this->presenter->presentValue($method)
        ));
    }
}
