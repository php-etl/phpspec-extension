<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\ETL\Metadata\IncompatibleTypeException;
use Kiboko\Component\ETL\Metadata\IterableTypeMetadataInterface;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveCompositedProperty extends BasicMatcher
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
        return $name === 'haveCompositedProperty' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($property) = $arguments;

        $typeDeclaration = $subject->getProperty($property)->getType();
        if ($typeDeclaration instanceof IterableTypeMetadataInterface) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property) = $arguments;

        return new FailureException(sprintf(
            'Expected the property %s to be a composite, but got %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($subject->getProperty($property)->getType())
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($property) = $arguments;

        return new FailureException(sprintf(
            'Expected the property %s to not be a composite.',
            $this->presenter->presentValue($property)
        ));
    }
}