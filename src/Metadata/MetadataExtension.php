<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;
use PhpSpec\ServiceContainer\IndexedServiceContainer;

final class MetadataExtension implements Extension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->define('matchers.have_composited_method_return', fn (IndexedServiceContainer $c) => new Matcher\HaveCompositedMethodReturn($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_composited_method_return_is_instance_of', fn (IndexedServiceContainer $c) => new Matcher\HaveCompositedMethodReturnIsInstanceOf($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_composited_method_return_is_type', fn (IndexedServiceContainer $c) => new Matcher\HaveCompositedMethodReturnIsType($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_composited_property', fn (IndexedServiceContainer $c) => new Matcher\HaveCompositedProperty($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_composited_property_is_instance_of', fn (IndexedServiceContainer $c) => new Matcher\HaveCompositedPropertyIsInstanceOf($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_composited_property_is_type', fn (IndexedServiceContainer $c) => new Matcher\HaveCompositedPropertyIsType($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_method_count', fn (IndexedServiceContainer $c) => new Matcher\HaveMethodCount($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_method_retrun_is_instance_of', fn (IndexedServiceContainer $c) => new Matcher\HaveMethodReturnIsInstanceOf($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_method_retrun_is_type', fn (IndexedServiceContainer $c) => new Matcher\HaveMethodReturnIsType($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_property_count', fn (IndexedServiceContainer $c) => new Matcher\HavePropertyCount($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_property_is_instance_of', fn (IndexedServiceContainer $c) => new Matcher\HavePropertyIsInstanceOf($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_property_is_type', fn (IndexedServiceContainer $c) => new Matcher\HavePropertyIsType($c->get('formatter.presenter')), ['matchers']);

        $container->define('matchers.have_type', fn (IndexedServiceContainer $c) => new Matcher\HaveMetadataType($c->get('formatter.presenter')), ['matchers']);
    }
}
