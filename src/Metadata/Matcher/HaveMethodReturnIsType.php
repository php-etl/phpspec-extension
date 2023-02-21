<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\Metadata\ArrayTypeMetadata;
use Kiboko\Component\Metadata\ClassReferenceMetadata;
use Kiboko\Component\Metadata\ClassTypeMetadata;
use Kiboko\Component\Metadata\CollectionTypeMetadata;
use Kiboko\Component\Metadata\ListTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveMethodReturnIsType extends BasicMatcher
{
    public function __construct(private readonly Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveMethodReturnIsType' && count($arguments) == 2;
    }

    protected function matches($subject, array $arguments): bool
    {
        [$method, $type] = $arguments;
        $typeDeclaration = $subject->getMethod($method)->getReturnType();
        if (($typeDeclaration instanceof ClassTypeMetadata && is_a((string) $typeDeclaration, $type, true)) ||
            ($typeDeclaration instanceof ClassReferenceMetadata && is_a((string) $typeDeclaration, $type, true)) ||
            ($typeDeclaration instanceof CollectionTypeMetadata && is_a((string) $typeDeclaration->getType(), $type, true)) ||
            ($typeDeclaration instanceof ListTypeMetadata && in_array($type, ['array', 'iterable'])) ||
            ($typeDeclaration instanceof ArrayTypeMetadata && in_array($type, ['array']))
        ) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$method, $type] = $arguments;
        return new NotEqualException(sprintf(
            'Expected method %s return to have %s type, but got %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($type),
            $this->presenter->presentValue($subject->getMethod($method)->getReturnType())
        ), $type, $subject->getMethod($method)->getReturnType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$method, $type] = $arguments;
        return new NotEqualException(sprintf(
            'Did not expect method %s return to have %s type.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($type)
        ), $type, $subject->getMethod($method)->getReturnType());
    }
}
