<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\DataProvider\Listener;

use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvidedExampleNode;
use Kiboko\Component\PHPSpecExtension\DataProvider\DataProvider;
use PhpSpec\Event\SpecificationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class DataProviderListener implements EventSubscriberInterface
{
    public function __construct(
        private DataProvider $dataProvider
    ) {
    }

    public static function getSubscribedEvents(): array
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
