<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\Metadata\Matcher;

use Kiboko\Component\ETL\Metadata\IncompatibleTypeException;
use Kiboko\Component\ETL\Metadata\IterableTypeMetadataInterface;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class HaveCompositedMethodReturn extends BasicMatcher
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
        return $name === 'haveCompositedMethodReturn' && count($arguments) == 1;
    }

    protected function matches($subject, array $arguments): bool
    {
        list($method) = $arguments;

        try {
            $typeDeclaration = $subject->getMethod($method)->getReturnType();
            if ($typeDeclaration instanceof IterableTypeMetadataInterface) {
                return true;
            }
        } catch (IncompatibleTypeException $e) {
            // Do nothing
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($method) = $arguments;

        return new FailureException(sprintf(
            'Expected the method %s to return a composite, but got %s.',
            $this->presenter->presentValue($method),
            $this->presenter->presentValue($subject->getMethod($method)->getReturnType())
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        list($method) = $arguments;

        return new FailureException(sprintf(
            'Expected the method %s to not return a composite.',
            $this->presenter->presentValue($method)
        ));
    }
}