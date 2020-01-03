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

final class HaveCompositedPropertyIsInstanceOf extends BasicMatcher
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
        return $name === 'haveCompositedPropertyIsInstanceOf' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($property, $class) = $arguments;

        $typeDeclaration = $subject->getProperty($property)->getType();
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
        list($property, $class) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the property %s to be an instance of %s composite, but got %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($class),
            $this->presenter->presentValue($subject->getProperty($property)->getType())
        ), $class, $subject->getProperty($property)->getType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property, $class) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the property %s to not be a composite with type %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($class)
        ), $class, $subject->getProperty($property)->getType());
    }
}