<?php
declare(strict_types=1);

namespace Application\Model\Abstracts;

use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;

class AbstractApplicationTableGateway extends AbstractTableGateway
{
    protected function hydrate($handler, string $entity)
    {
        if (!$handler)
            return null;

        $hydrator = new ClassMethodsHydrator();
        $entity = new $entity();

        $hydrator->hydrate($handler, $entity);

        return $entity;
    }

    protected function executeStatement($query): ResultInterface
    {
        return $this->sql->prepareStatementForSqlObject($query)->execute();
    }

    protected function collectionFromSet(ResultInterface $set, string $entity): array
    {
        if (!$set->isQueryResult())
            return [];

        $collection = [];
        $row = $set->current();

        if (!empty($row))
            $collection[] = $this->hydrate($row, $entity);

        while ($row = $set->next())
            $collection[] = $this->hydrate($row, $entity);

        return $collection;
    }
}