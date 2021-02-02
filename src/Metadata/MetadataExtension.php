<?php declare(strict_types=1);

namespace Kiboko\Component\PHPSpecExtension\Metadata;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;
use PhpSpec\ServiceContainer\IndexedServiceContainer;

final class MetadataExtension implements Extension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->define('matchers.have_composited_method_return', function (IndexedServiceContainer $c) {
            return new Matcher\HaveCompositedMethodReturn($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_composited_method_return_is_instance_of', function (IndexedServiceContainer $c) {
            return new Matcher\HaveCompositedMethodReturnIsInstanceOf($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_composited_method_return_is_type', function (IndexedServiceContainer $c) {
            return new Matcher\HaveCompositedMethodReturnIsType($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_composited_property', function (IndexedServiceContainer $c) {
            return new Matcher\HaveCompositedProperty($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_composited_property_is_instance_of', function (IndexedServiceContainer $c) {
            return new Matcher\HaveCompositedPropertyIsInstanceOf($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_composited_property_is_type', function (IndexedServiceContainer $c) {
            return new Matcher\HaveCompositedPropertyIsType($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_method_count', function (IndexedServiceContainer $c) {
            return new Matcher\HaveMethodCount($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_method_retrun_is_instance_of', function (IndexedServiceContainer $c) {
            return new Matcher\HaveMethodReturnIsInstanceOf($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_method_retrun_is_type', function (IndexedServiceContainer $c) {
            return new Matcher\HaveMethodReturnIsType($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_property_count', function (IndexedServiceContainer $c) {
            return new Matcher\HavePropertyCount($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_property_is_instance_of', function (IndexedServiceContainer $c) {
            return new Matcher\HavePropertyIsInstanceOf($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_property_is_type', function (IndexedServiceContainer $c) {
            return new Matcher\HavePropertyIsType($c->get('formatter.presenter'));
        }, ['matchers']);

        $container->define('matchers.have_type', function (IndexedServiceContainer $c) {
            return new Matcher\HaveMetadataType($c->get('formatter.presenter'));
        }, ['matchers']);
    }
}
