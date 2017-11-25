<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class CategoryRepository
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function findAll()
    {
        $sql = new Sql($this->dbAdapter);

        $categorySelect     = $sql->select('category');
        $categoryStatement  = $sql->prepareStatementForSqlObject($categorySelect);
        $categoryResult     = $categoryStatement->execute();

        $categoryResultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Category()
        );

        $categoryResultSet->initialize($categoryResult);

        $categories = [];

        /**
         * @var Category $category
         */
        foreach ($categoryResultSet as $category) {
            $noteSelect     = $sql->select('note')->where(['category_id' => $category->getId()]);
            $noteStatement  = $sql->prepareStatementForSqlObject($noteSelect);
            $noteResult     = $noteStatement->execute();

            $noteResultSet = new HydratingResultSet(
                new ReflectionHydrator(),
                new Note()
            );

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
        $sql = new Sql($this->dbAdapter);

        $categorySelect     = $sql->select('category')->where(['id' => $id]);
        $categoryStatement  = $sql->prepareStatementForSqlObject($categorySelect);
        $categoryResult     = $categoryStatement->execute();

        if (!$categoryResult->valid()) {
            throw new \InvalidArgumentException(sprintf('Category with identifier "%s" not found', $id));
        }

        $categoryResultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Category()
        );

        $categoryResultSet->initialize($categoryResult);

        /** @var Category $category */
        $category = $categoryResultSet->current();

        $noteSelect     = $sql->select('note')->where(['category_id' => $category->getId()]);
        $noteStatement  = $sql->prepareStatementForSqlObject($noteSelect);
        $noteResult     = $noteStatement->execute();

        $noteResultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Note()
        );

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
