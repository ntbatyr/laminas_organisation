<?php

declare(strict_types=1);

namespace Auth;

use Auth\Model\UsersTable;
use Interop\Container\Containerinterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                UsersTable::class => function (ContainerInterface  $container) {
                    $dbAdapter = $container->get(Adapter::class);

                    return new UsersTable($dbAdapter);
                }
            ]
        ];
    }
}
