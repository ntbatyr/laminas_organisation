<?php

declare(strict_types = 1);

namespace Auth\Controller\Factory;

use Auth\Controller\LoginController;
use Auth\Model\UsersTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class LoginControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LoginController
    {
        return new LoginController(
            $container->get(Adapter::class),
            $container->get(UsersTable::class)
        );
    }


}