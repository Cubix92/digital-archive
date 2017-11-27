<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class CategoryRepository extends AdapterAbstract
{
    public function findAll()
    {
        $categories = [];
        $categorySelect = new Select('category');
        $categoryResult = $this->executeStatement($categorySelect);

        $categoryResultSet = new HydratingResultSet(new ReflectionHydrator(), new Category());
        $categoryResultSet->initialize($categoryResult);

        /**
         * @var Category $category
         */
        foreach ($categoryResultSet as $category) {
            $noteSelect = (new Select('note'))->where(['category_id' => $category->getId()]);
            $noteResult = $categoryResult = $this->executeStatement($noteSelect);

            $noteResultSet = new HydratingResultSet(new ReflectionHydrator(),new Note());
            $noteResultSet->initialize($noteResult);

            /**
             * @var Note $note
             */
            foreach ($noteResultSet as $note) {
                $category->addNote($note);
            }

            $categories[] = $category;
        }

        return $categories;
    }

    public function findById($id): Category
    {
        $categorySelect = (new Select('category'))->where(['id' => $id]);
        $categoryResult = $this->executeStatement($categorySelect);

        if (!$categoryResult->valid()) {
            throw new \UnexpectedValueException('Category not found');
        }

        $categoryResultSet = new HydratingResultSet(new ReflectionHydrator(), new Category());
        $categoryResultSet->initialize($categoryResult);

        /** @var Category $category */
        $category = $categoryResultSet->current();
        $noteSelect = (new Select('note'))->where(['category_id' => $category->getId()]);
        $noteResult = $this->executeStatement($noteSelect);

        $noteResultSet = new HydratingResultSet(new ReflectionHydrator(), new Note());
        $noteResultSet->initialize($noteResult);

        /**
         * @var Note $note
         */
        foreach ($noteResultSet as $note) {
            $category->addNote($note);
        }

        return $category;
    }
}
