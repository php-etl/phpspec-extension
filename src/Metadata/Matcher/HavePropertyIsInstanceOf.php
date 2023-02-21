<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\Metadata\ClassReferenceMetadata;
use Kiboko\Component\Metadata\ClassTypeMetadata;
use Kiboko\Component\Metadata\CollectionTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HavePropertyIsInstanceOf extends BasicMatcher
{
    public function __construct(private readonly Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'havePropertyIsInstanceOf' && count($arguments) == 2;
    }

    protected function matches($subject, array $arguments): bool
    {
        [$property, $class] = $arguments;

        $typeDeclaration = $subject->getProperty($property)->getType();
        if (($typeDeclaration instanceof ClassTypeMetadata && is_a((string) $typeDeclaration, $class, true)) ||
            ($typeDeclaration instanceof ClassReferenceMetadata && is_a((string) $typeDeclaration, $class, true)) ||
            ($typeDeclaration instanceof CollectionTypeMetadata && is_a((string) $typeDeclaration->getType(), $class, true))
        ) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$property, $class] = $arguments;

        return new NotEqualException(sprintf(
            'Expected property %s to be an instance of %s, but got %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($class),
            $this->presenter->presentValue($subject->getProperty($property)->getType())
        ), $class, $subject->getProperty($property)->getType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$property, $class] = $arguments;

        return new NotEqualException(sprintf(
            'Did not expect property %s to be an instance of %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($class)
        ), $class, $subject->getProperty($property)->getType());
    }
}
