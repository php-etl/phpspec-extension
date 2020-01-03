<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\ETL\Metadata\ArrayTypeMetadata;
use Kiboko\Component\ETL\Metadata\ClassReferenceMetadata;
use Kiboko\Component\ETL\Metadata\ClassTypeMetadata;
use Kiboko\Component\ETL\Metadata\CollectionTypeMetadata;
use Kiboko\Component\ETL\Metadata\ListTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveMethodReturnIsType extends BasicMatcher
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
        return $name === 'haveMethodReturnIsType' && count($arguments) == 2;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($method, $type) = $arguments;
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
        list($method, $type) = $arguments;
        return new NotEqualException(sprintf(
            'Expected method %s return to have %s type, but got %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($type),
            $this->presenter->presentValue($subject->getMethod($method)->getReturnType())
        ), $type, $subject->getMethod($method)->getReturnType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($method, $type) = $arguments;
        return new NotEqualException(sprintf(
            'Did not expect method %s return to have %s type.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($type)
        ), $type, $subject->getMethod($method)->getReturnType());
    }
}