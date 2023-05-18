<?php

namespace Application\Listener;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;
use Psr\Container\ContainerInterface;

class LocaleListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LocaleListener
    {
        return new LocaleListener($container, new Container());
    }
}