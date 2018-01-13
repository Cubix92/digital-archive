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
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql($dbAdapter);
    }

    public function insert(Category $category)
    {
        $insert = new Insert('category');

        $insert->values([
            'name' => $category->getName(),
            'shortcut' => $category->getShortcut()
        ]);

        $result = $this->sql->prepareStatementForSqlObject($insert)->execute();
        $id = $result->getGeneratedValue();

        return $id;
    }

    public function update(Category $category)
    {
        if (!$category->getId()) {
            throw new \RuntimeException('Cannot update category; missing identifier.');
        }

        $update = new Update('category');

        $update->set([
            'name' => $category->getName(),
            'shortcut' => $category->getShortcut()
        ]);
        $update->where(['id = ?' => $category->getId()]);

        $this->sql->prepareStatementForSqlObject($update)->execute();
    }

    public function delete(Category $category)
    {
        if ($category->getNotes()) {
            throw new \RuntimeException('Cannot delete category; category has related values');
        }

        $delete = (new Delete('category'))
            ->where(['id' => $category->getId()]);

        $this->sql->prepareStatementForSqlObject($delete)->execute();
    }
}
