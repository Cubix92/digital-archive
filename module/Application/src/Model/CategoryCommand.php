<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class CategoryCommand extends AdapterAbstract
{
    public function insert(Category $category)
    {
        $insert = new Insert('category');

        $insert->values([
            'name' => $category->getName(),
            'icon' => $category->getIcon(),
            'position' => $category->getPosition() ? $category->getPosition() : 0
        ]);

        $result = $this->executeStatement($insert);
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

        $this->executeStatement($update);
    }

    public function delete(Category $category)
    {
        if ($category->getNotes()) {
            throw new \RuntimeException('Cannot delete category; category has related values.');
        }

        if (!$category->getId()) {
            throw new \RuntimeException('Cannot delete category; missing identifier.');
        }

        $delete = (new Delete('category'))
            ->where(['id' => $category->getId()]);

        $this->executeStatement($delete);
    }
}
