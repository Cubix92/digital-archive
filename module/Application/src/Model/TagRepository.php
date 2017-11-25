<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Hydrator\Reflection;

class TagRepository
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function findAll()
    {
        $sql = new Sql($this->dbAdapter);

        $select     = $sql->select('tag');
        $statement  = $sql->prepareStatementForSqlObject($select);
        $result     = $statement->execute();

        $resultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Tag()
        );

        $resultSet->initialize($result);

        $tags = [];

        /**
         * @var Tag $tag
         */
        foreach ($resultSet as $tag) {
            $tags[] = $tag;
        }

        return $tags;
    }

    public function findById($id): Tag
    {
        $sql = new Sql($this->dbAdapter);

        $select     = $sql->select('tag')->where(['id' => $id]);
        $statement  = $sql->prepareStatementForSqlObject($select);
        $result     = $statement->execute();

        if (!$result->valid()) {
            throw new \InvalidArgumentException(sprintf('Tag with identifier "%s" not found', $id));
        }

        $resultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Tag()
        );

        $resultSet->initialize($result);

        /** @var Tag $tag */
        $tag = $resultSet->current();

        $notesSelect = $sql->select('tag')
            ->join('note_tag', 'note_tag.tag_id = tag.id')
            ->join('note', 'note.id = note_tag.note_id')
            ->where(['tag.id' => $id]);

        $notesStatement  = $sql->prepareStatementForSqlObject($notesSelect);
        $notesResult = $notesStatement->execute();

        $noteResultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Note()
        );

        $noteResultSet->initialize($notesResult);

        /** @var Note $note */
        foreach($noteResultSet as $note) {
            $tag->addNote($note);
        }

        return $tag;
    }
}
