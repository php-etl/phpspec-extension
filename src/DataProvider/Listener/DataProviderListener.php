<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\PHPSpecExtension\DataProvider\Listener;

use Kiboko\Component\ETL\PHPSpecExtension\DataProvider\DataProvidedExampleNode;
use Kiboko\Component\ETL\PHPSpecExtension\DataProvider\DataProvider;
use PhpSpec\Event\SpecificationEvent;
use PhpSpec\Loader\Node\ExampleNode;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class DataProviderListener implements EventSubscriberInterface
{
    /** @var DataProvider */
    private $dataProvider;

    public function __construct(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public static function getSubscribedEvents()
    {
        return [
            'beforeSpecification' => ['beforeSpecification'],
        ];
    }

    public function beforeSpecification(SpecificationEvent $event)
    {
        foreach ($event->getSpecification()->getExamples() as $example) {
            if (!$this->dataProvider->supports($example)) {
                continue;
            }

            $example->markAsPending();
            foreach ($this->dataProvider->walk($example) as $key => $dataRow) {
                $event->getSpecification()->addExample(
                    new DataProvidedExampleNode(sprintf('  <value>%s</value> %s', $key, $example->getTitle()), $example, $dataRow)
                );
            }
        }
    }
}