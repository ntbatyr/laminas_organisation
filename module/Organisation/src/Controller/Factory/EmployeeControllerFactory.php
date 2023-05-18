<?php
declare(strict_types=1);

namespace Organisation\Controller\Factory;

use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Organisation\Controller\EmployeeController;
use Organisation\Model\EmployeeTable;
use Psr\Container\ContainerInterface;

class EmployeeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): EmployeeController
    {
        return new EmployeeController(
            $container->get(Adapter::class),
            $container->get(EmployeeTable::class)
        );
    }
}