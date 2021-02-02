<?php declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\DataProvider;

use PhpSpec\Loader\Node\ExampleNode;

final class DataProvidedExampleNode extends ExampleNode
{
    public function __construct(string $title, private ExampleNode $parent, private array $providedData)
    {
        parent::__construct($title, $parent->getFunctionReflection());
    }

    public function getParent(): ExampleNode
    {
        return $this->parent;
    }

    public function getProvidedData(): array
    {
        return $this->providedData;
    }
}
