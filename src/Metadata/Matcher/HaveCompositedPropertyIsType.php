<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\ETL\Metadata\CollectionTypeMetadata;
use Kiboko\Component\ETL\Metadata\ListTypeMetadata;
use Kiboko\Component\ETL\Metadata\ScalarTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveCompositedPropertyIsType extends BasicMatcher
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
        return $name === 'haveCompositedPropertyIsType' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($property, $type) = $arguments;

        $typeDeclaration = $subject->getProperty($property)->getType();
        if ($typeDeclaration instanceof ListTypeMetadata &&
            $typeDeclaration->getInner() instanceof ScalarTypeMetadata &&
            is_a($typeDeclaration->getInner()->name, $type, true)
        ) {
            return true;
        }

        if ($typeDeclaration instanceof CollectionTypeMetadata &&
            $typeDeclaration->getType() instanceof ScalarTypeMetadata &&
            is_a($typeDeclaration->getType()->name, $type, true)
        ) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property, $type) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the property %s to be a %s composite type, but got %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($type),
            $this->presenter->presentValue($subject->getProperty($property)->getType())
        ), $type, $subject->getProperty($property)->getType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property, $type) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the property %s to not be a composite type.',
            $this->presenter->presentValue($property)
        ), $type, $subject->getProperty($property)->getType());
    }
}