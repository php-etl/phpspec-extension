<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\ETL\Metadata\ClassReferenceMetadata;
use Kiboko\Component\ETL\Metadata\ClassTypeMetadata;
use Kiboko\Component\ETL\Metadata\CollectionTypeMetadata;
use Kiboko\Component\ETL\Metadata\ListTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveCompositedMethodReturnIsInstanceOf extends BasicMatcher
{
    /**
     * @var Presenter
     */
    private $presenter;

    /**
     * @param Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveCompositedMethodReturnIsInstanceOf' && count($arguments) == 2;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($method, $class) = $arguments;

        $typeDeclaration = $subject->getMethod($method)->getReturnType();
        if ($typeDeclaration instanceof ListTypeMetadata &&
            ($typeDeclaration->getInner() instanceof ClassTypeMetadata || $typeDeclaration->getInner() instanceof ClassReferenceMetadata) &&
            is_a((string) $typeDeclaration->getInner(), $class, true)
        ) {
            return true;
        }

        if ($typeDeclaration instanceof CollectionTypeMetadata &&
            ($typeDeclaration->getType() instanceof ClassTypeMetadata || $typeDeclaration->getType() instanceof ClassReferenceMetadata) &&
            is_a((string) $typeDeclaration->getType(), $class, true)
        ) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($method, $class) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the method %s to return a composite with type %s, but got %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($class),
            $this->presenter->presentValue($subject->getMethod($method)->getReturnType())
        ), $class, $subject->getMethod($method)->getReturnType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($method, $class) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the method %s to not return a composite with type %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($class),
        ), $class, $subject->getMethod($method)->getReturnType());
    }
}