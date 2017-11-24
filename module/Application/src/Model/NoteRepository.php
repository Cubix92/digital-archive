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

    public function __construct(NoteHydrator $noteHydrator, AdapterInterface $dbAdapter)
    {
        $this->noteHydrator = $noteHydrator;
        $this->dbAdapter = $dbAdapter;
    }

    public function findAll()
    {
        $sql = new Sql($this->dbAdapter);

        $noteSelect     = $sql->select('note');
        $noteStatement  = $sql->prepareStatementForSqlObject($noteSelect);
        $noteResult     = $noteStatement->execute();

        $noteResultSet = new HydratingResultSet(
            $this->noteHydrator,
            new Note()
        );

        $noteResultSet->initialize($noteResult);

        $notes = [];

        /**
         * @var Note $note
         */
        foreach ($noteResultSet as $note) {
            $categorySelect     = $sql->select('category')->where(['id' => $note->getCategory()->getId()]);
            $categoryStatement  = $sql->prepareStatementForSqlObject($categorySelect);
            $categoryResult     = $categoryStatement->execute();

            $categoryResultSet = new HydratingResultSet(
                new ReflectionHydrator(),
                new Category()
            );

            /** @var Category $category */
            $categoryResultSet->initialize($categoryResult);
            $category = $categoryResultSet->current();
            $note->setCategory($category);
            $notes[] = $note;
        }

        return $notes;
    }

    public function findById($id): Note
    {
        $sql = new Sql($this->dbAdapter);

        $noteSelect     = $sql->select('note')->where(['id' => $id]);
        $noteStatement  = $sql->prepareStatementForSqlObject($noteSelect);
        $noteResult     = $noteStatement->execute();

        if (!$noteResult->valid()) {
            throw new \InvalidArgumentException(sprintf('Note with identifier "%s" not found', $id));
        }

        $noteResultSet = new HydratingResultSet(
            $this->noteHydrator,
            new Note()
        );

        $noteResultSet->initialize($noteResult);

        /** @var Category $category */
        $note = $noteResultSet->current();

        return $note;
    }
}
