<?php

namespace Application\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class CategoryTable
{
    protected $tableGateway;

    protected $categoryHydrator;

    public function __construct(TableGateway $tableGateway, CategoryHydrator $categoryHydrator)
    {
         $this->tableGateway = $tableGateway;
         $this->categoryHydrator = $categoryHydrator;
    }

    public function findAll()
    {
        return $this->tableGateway->select();
    }

    public function findById($id): Category
    {
        $result = $this->tableGateway->select(['id' => $id]);

        if (!$result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new \RuntimeException(sprintf(
                'Failed retrieving category with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->categoryHydrator, new Category());
        $resultSet->initialize($result);
        $note = $resultSet->current();

        if (!$note) {
            throw new \InvalidArgumentException(sprintf(
                'Category with identifier "%s" not found.',
                $id
            ));
        }

        return $note;
    }
}
