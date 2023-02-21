<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Contract\Metadata\IterableTypeMetadataInterface;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveCompositedProperty extends BasicMatcher
{
    public function __construct(private readonly Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveCompositedProperty' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        [$property] = $arguments;

        $typeDeclaration = $subject->getProperty($property)->getType();
        if ($typeDeclaration instanceof IterableTypeMetadataInterface) {
            return true;
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$property] = $arguments;

        return new FailureException(sprintf(
            'Expected the property %s to be a composite, but got %s.',
            $this->presenter->presentValue($property),
            $this->presenter->presentValue($subject->getProperty($property)->getType())
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$property] = $arguments;

        return new FailureException(sprintf(
            'Expected the property %s to not be a composite.',
            $this->presenter->presentValue($property)
        ));
    }
}
