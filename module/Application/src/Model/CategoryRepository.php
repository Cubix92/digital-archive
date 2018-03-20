<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class CategoryRepository
{
    protected $sql;

    protected $categoryHydrator;

    public function __construct(AdapterInterface $dbAdapter, CategoryHydrator $categoryHydrator)
    {
        $this->sql = new Sql($dbAdapter);
        $this->categoryHydrator = $categoryHydrator;
    }

    public function findAll(): array
    {
        $categories = [];
        $categorySelect = new Select('category');
        $categoryResult = $this->sql->prepareStatementForSqlObject($categorySelect)->execute();

        $categoryResultSet = new ResultSet();
        $categoryResultSet->initialize($categoryResult);

        /**
         * @var Category $category
         */
        foreach ($categoryResultSet->toArray() as $category) {
            $noteSelect = (new Select('note'))->where(['category' => $category['id']]);
            $noteResult = $this->sql->prepareStatementForSqlObject($noteSelect)->execute();

            $noteResultSet = new ResultSet();
            $noteResultSet->initialize($noteResult);

            /**
             * @var Note $note
             */
            foreach ($noteResultSet->toArray() as $note) {
                $category['note'] = $note;
            }

            $categories[] = $this->categoryHydrator->hydrate($category, new Category());
        }

        return $categories;
    }

    public function findById($id): Category
    {
        $categorySelect = (new Select('category'))->where(['id' => $id]);
        $categoryResult = $this->sql->prepareStatementForSqlObject($categorySelect)->execute();

        if (!$categoryResult->valid()) {
            throw new \UnexpectedValueException('Category not found');
        }

        $categoryResultSet = new HydratingResultSet(new ReflectionHydrator(), new Category());
        $categoryResultSet->initialize($categoryResult);

        /** @var Category $category */
        $category = $categoryResultSet->current();
        $noteSelect = (new Select('note'))->where(['category' => $category->getId()]);
        $noteResult = $this->sql->prepareStatementForSqlObject($noteSelect)->execute();

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
