<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\ETL\Metadata\ArrayTypeMetadata;
use Kiboko\Component\ETL\Metadata\ListTypeMetadata;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HavePropertyIsType extends BasicMatcher
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
        return $name === 'havePropertyIsType' && count($arguments) == 2;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($property, $type) = $arguments;

        $typeDeclaration = $subject->getProperty($property)->getType();
        if (($typeDeclaration instanceof ListTypeMetadata && in_array($type, ['array', 'iterable'])) ||
            ($typeDeclaration instanceof ArrayTypeMetadata && in_array($type, ['array']))
        ) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property, $type) = $arguments;

        return new NotEqualException(sprintf(
            'Expected the property %s to be a %s type, but got %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($type),
            $this->presenter->presentValue($subject->getProperty($property)->getType())
        ), $type, $subject->getProperty($property)->getType());
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property, $type) = $arguments;

        return new NotEqualException(sprintf(
            'Did not expect property %s to be a %s type.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($type)
        ), $type, $subject->getProperty($property)->getType());
    }
}