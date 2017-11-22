<?php

namespace Application\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class CategoryTable
{
    protected $tableGateway;

    protected $categoryHydrator;

    public function __construct(TableGateway $tableGateway)
    {
         $this->tableGateway = $tableGateway;
    }

    public function findAll()
    {
        return $this->tableGateway->select();
    }

    public function findById($id): Category
    {
        /** @var Category $category */
        $categoryResult = $this->tableGateway->select(['id' => $id]);
        $category = $categoryResult->current();

        if (!$category) {
            throw new \InvalidArgumentException(sprintf(
                'Category with identifier "%s" not found.',
                $id
            ));
        }

        return $category;
    }

    public function save(Category $category)
    {
        $data = [
            'name' => $category->getName(),
            'icon' => $category->getIcon(),
            'position' => 0
        ];

        $id = $category->getId();

        if ($id == 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->findById($id)) {
            throw new \RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
}
