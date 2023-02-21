<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\FastMap;

use PhpSpec\Extension;
use PhpSpec\Factory\ReflectionFactory;
use PhpSpec\ServiceContainer;
use PhpSpec\ServiceContainer\IndexedServiceContainer;

final class FastMapExtension implements Extension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->define('matchers.execute_compiled_mapping', fn (IndexedServiceContainer $c) => new Matcher\ExecuteCompiledMapping($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.execute_uncompiled_mapping', fn (IndexedServiceContainer $c) => new Matcher\ExecuteUncompiledMapping($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.throw_when_execute_uncompiled_mapping', fn (IndexedServiceContainer $c) => new Matcher\ThrowWhenExecuteCompiledMappingMatcher($c->get('unwrapper'), $c->get('formatter.presenter'), new ReflectionFactory()), ['matchers']);
    }
}
