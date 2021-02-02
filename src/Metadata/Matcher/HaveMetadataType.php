<?php declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\Metadata\Type;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveMetadataType extends BasicMatcher
{
    public function __construct(private Presenter $presenter)
    {
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'haveMetadataType' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($type) = $arguments;

        return Type::is($type, $subject);
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($type) = $arguments;

        if (!is_a(get_class($subject), get_class($type))) {
            return new NotEqualException(sprintf(
                'Expected to have %s type, but got %s.',
                $this->presenter->presentValue(get_class($type)),
                $this->presenter->presentValue(get_class($subject))
            ), get_class($type), get_class($subject));
        }

        return new FailureException(sprintf(
            'Expected to have %s type, but got %s.',
            $this->presenter->presentValue($type),
            $this->presenter->presentValue($subject)
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($type) = $arguments;

        return new FailureException(sprintf(
            'Did not expect to have %s type.',
            $this->presenter->presentValue($type)
        ));
    }
}
