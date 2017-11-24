<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class NoteRepository
{
    protected $noteHydrator;

    protected $dbAdapter;

    public function __construct(NoteHydrator $noteHydrator, AdapterInterface  $dbAdapter)
    {
         $this->noteHydrator = $noteHydrator;
         $this->dbAdapter = $dbAdapter;
    }

    public function findAll()
    {
        $sql                = new Sql($this->dbAdapter);
        $noteSelect     = $sql->select('note');
        $noteStatement  = $sql->prepareStatementForSqlObject($noteSelect);
        $noteResult     = $noteStatement->execute();

        if (! $noteResult instanceof ResultInterface || ! $noteResult->isQueryResult()) {
            return [];
        }

        $noteResultSet = new HydratingResultSet(
            $this->noteHydrator,
            new Note()
        );

        $noteResultSet->initialize($noteResult);

        $notes = [];

        /**
         * @var Note $note
         */
        foreach($noteResultSet as $note) {
            $categorySelect     = $sql->select('category')->where(['id' => $note->getCategory()->getId()]);
            $categoryStatement  = $sql->prepareStatementForSqlObject($categorySelect);
            $categoryResult     = $categoryStatement->execute();

            $categoryResultSet = new HydratingResultSet(
                new ReflectionHydrator(),
                new Category()
            );

            $categoryResultSet->initialize($categoryResult);
            /** @var Category $category */
            $category = $categoryResultSet->current();
            $note->setCategory($category);
            $notes[] = $note;
        }

        return $notes;
    }

    public function findById($id): Category
    {
        $sql                = new Sql($this->dbAdapter);
        $categorySelect     = $sql->select('category')->where(['id' => $id]);
        $categoryStatement  = $sql->prepareStatementForSqlObject($categorySelect);
        $categoryResult     = $categoryStatement->execute();

        if (! $categoryResult instanceof ResultInterface || !$categoryResult->isQueryResult()) {
            throw new \InvalidArgumentException(sprintf('User with identifier "%s" not found', $id));
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
        foreach($noteResultSet as $note) {
            $category->addNote($note);
        }

        return $category;
    }
}
