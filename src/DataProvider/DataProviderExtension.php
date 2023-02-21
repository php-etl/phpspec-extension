<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\DataProvider;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;

final class DataProviderExtension implements Extension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->define('event_dispatcher.listeners.data_provider', fn ($c) => new Listener\DataProviderListener(new DataProvider()), ['event_dispatcher.listeners']);

        $container->define('runner.maintainers.data_provider', fn ($c) => new Runner\Maintainer\DataProviderMaintainer(), ['runner.maintainers']);
    }
}
