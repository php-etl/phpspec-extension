<?php declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\DataProvider;

use PhpSpec\Loader\Node\ExampleNode;

final class DataProvidedExampleNode extends ExampleNode
{
    /** @var ExampleNode */
    private $parent;
    /** @var mixed[] */
    private $providedData;

    public function __construct(string $title, ExampleNode $parent, array $providedData)
    {
        parent::__construct($title, $parent->getFunctionReflection());
        $this->parent = $parent;
        $this->providedData = $providedData;
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
