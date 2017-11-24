<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class CategoryCommand
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function insert(Category $category)
    {
        $insert = new Insert('category');

        $insert->values([
            'name' => $category->getName(),
            'icon' => $category->getIcon(),
            'position' => $category->getPosition() ? $category->getPosition() : 0
        ]);

        $sql = new Sql($this->dbAdapter);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new \RuntimeException(
                'Database error occurred during category insert operation.'
            );
        }

        $id = $result->getGeneratedValue();
        return $id;
    }

    public function update(Category $category)
    {
        if (!$category->getId()) {
            throw new \RuntimeException('Cannot update post; missing identifier.');
        }

        $update = new Update('category');

        $update->set([
            'name' => $category->getName(),
            'icon' => $category->getIcon(),
            'position' => $category->getPosition()
        ]);
        $update->where(['id = ?' => $category->getId()]);

        $sql = new Sql($this->dbAdapter);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new \RuntimeException(
                'Database error occurred during category update operation'
            );
        }

        return $category;
    }

    public function delete(Category $category)
    {
        if ($category->getNotes()) {
            throw new \RuntimeException('Cannot delete category; category has related values.');
        }

        if (!$category->getId()) {
            throw new \RuntimeException('Cannot delete category; missing identifier.');
        }

        $delete = new Delete('category');
        $delete->where(['id' => $category->getId()]);

        $sql = new Sql($this->dbAdapter);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new \RuntimeException('Cannot delete category; undefined erro.');
        }
    }
}
