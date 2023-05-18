<?php
declare(strict_types=1);

namespace Organisation;

use Interop\Container\Containerinterface;
use Laminas\Db\Adapter\Adapter;
use Organisation\Model\DepartmentsTable;
use Organisation\Model\EmployeeTable;

class Module
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
                DepartmentsTable::class => function (ContainerInterface  $container) {
                    $dbAdapter = $container->get(Adapter::class);

                    return new DepartmentsTable($dbAdapter);
                },
                EmployeeTable::class => function (ContainerInterface  $container) {
                    $dbAdapter = $container->get(Adapter::class);

                    return new EmployeeTable($dbAdapter);
                }
            ]
        ];
    }
}
