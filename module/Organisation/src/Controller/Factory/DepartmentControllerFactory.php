<?php
declare(strict_types=1);

namespace Organisation\Controller\Factory;

use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Organisation\Controller\DepartmentController;
use Organisation\Model\DepartmentEmployeesTable;
use Organisation\Model\DepartmentsTable;
use Psr\Container\ContainerInterface;

class DepartmentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DepartmentController
    {
        return new DepartmentController(
            $container->get(Adapter::class),
            $container->get(DepartmentsTable::class),
            $container->get(DepartmentEmployeesTable::class)
        );
    }
}