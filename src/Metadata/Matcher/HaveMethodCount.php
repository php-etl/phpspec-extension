<?php declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveMethodCount extends BasicMatcher
{
    public function __construct(private Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveMethodCount' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($count) = $arguments;

        return count($subject->getMethods()) === $count;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($count) = $arguments;

        return new NotEqualException(sprintf(
            'Expected %d methods to be declared, but got %d.',
            $this->presenter->presentValue($count),
            $this->presenter->presentValue(count($subject->getMethods()))
        ), $count, count($subject->getMethods()));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($count) = $arguments;

        return new NotEqualException(sprintf(
            'Did not expect %d methods to be declared, but got %d.',
            $this->presenter->presentValue($count),
            $this->presenter->presentValue(count($subject->getMethods()))
        ), $count, count($subject->getMethods()));
    }
}
