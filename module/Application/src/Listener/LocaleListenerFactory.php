<?php

namespace Application\Listener;

use Laminas\Di\CodeGenerator\FactoryInterface;
use Psr\Container\ContainerInterface;

class LocaleListenerFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\Containerinterface $container, $name, array $options = null): LocaleListener
    {
        return $this->create($container, $options);
    }

    public function create(ContainerInterface $container, array $options): LocaleListener
    {
        return new LocaleListener($container);
    }
}