<?php declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\Metadata\ClassReferenceMetadata;
use Kiboko\Component\Metadata\ClassTypeMetadata;
use Kiboko\Component\Metadata\CollectionTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveMethodReturnIsInstanceOf extends BasicMatcher
{
    public function __construct(private Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveMethodReturnIsInstanceOf' && count($arguments) == 2;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($method, $class) = $arguments;

        $typeDeclaration = $subject->getMethod($method)->getReturnType();
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
        list($method, $class) = $arguments;

        return new NotEqualException(sprintf(
            'Expected method %s return to have %s type, but got %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($class),
            $this->presenter->presentValue($subject->getMethod($method)->getReturnType())
        ), $class, $subject->getMethod($method)->getReturnType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($method, $class) = $arguments;

        return new NotEqualException(sprintf(
            'Did not expect method %s return to have %s type.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($class)
        ), $class, $subject->getMethod($method)->getReturnType());
    }
}
