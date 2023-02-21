<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveMethodCount extends BasicMatcher
{
    public function __construct(private readonly Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveMethodCount' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        [$count] = $arguments;

        return (is_countable($subject->getMethods()) ? count($subject->getMethods()) : 0) === $count;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$count] = $arguments;

        return new NotEqualException(sprintf(
            'Expected %d methods to be declared, but got %d.',
            $this->presenter->presentValue($count),
            $this->presenter->presentValue(is_countable($subject->getMethods()) ? count($subject->getMethods()) : 0)
        ), $count, is_countable($subject->getMethods()) ? count($subject->getMethods()) : 0);
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        [$count] = $arguments;

        return new NotEqualException(sprintf(
            'Did not expect %d methods to be declared, but got %d.',
            $this->presenter->presentValue($count),
            $this->presenter->presentValue(is_countable($subject->getMethods()) ? count($subject->getMethods()) : 0)
        ), $count, is_countable($subject->getMethods()) ? count($subject->getMethods()) : 0);
    }
}
