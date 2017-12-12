<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Hydrator\Reflection;

class TagRepository
{
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql($dbAdapter);
    }

    public function findAll()
    {
        $tags = [];
        $tagSelect = new Select('tag');
        $result = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();

        $resultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Tag()
        );

        $resultSet->initialize($result);

        /**
         * @var Tag $tag
         */
        foreach ($resultSet as $tag) {
            $tags[] = $tag;
        }

        return $tags;
    }

    public function findUnassigned()
    {
        $tags = [];
        $tagSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', '*', Select::JOIN_LEFT)
            ->where('nt.tag_id IS NULL');

        $result = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();

        $resultSet = new HydratingResultSet(
            new ReflectionHydrator(),
            new Tag()
        );

        $resultSet->initialize($result);

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
        $tagSelect = (new Select('tag'))->where(['id' => $id]);
        $result = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();

        if (!$result->valid()) {
            throw new \UnexpectedValueException('Tag not found');
        }

        $resultSet = new HydratingResultSet(new ReflectionHydrator(), new Tag());
        $resultSet->initialize($result);

        /** @var Tag $tag */
        $tag = $resultSet->current();

        $notesSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id')
            ->join(['n' => 'note'], 'n.id = nt.note_id')
            ->where(['t.id' => $id]);

        $notesResult = $this->sql->prepareStatementForSqlObject($notesSelect)->execute();

        $noteResultSet = new HydratingResultSet(new ReflectionHydrator(), new Note());
        $noteResultSet->initialize($notesResult);

        /** @var Note $note */
        foreach($noteResultSet as $note) {
            $tag->addNote($note);
        }

        return $tag;
    }

    public function findByName($name): Tag
    {
        $tagSelect = (new Select('tag'))->where(['name' => $name]);
        $result = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();

        if (!$result->valid()) {
            throw new \UnexpectedValueException('Tag not found');
        }

        $resultSet = new HydratingResultSet(new ReflectionHydrator(), new Tag());
        $resultSet->initialize($result);

        /** @var Tag $tag */
        $tag = $resultSet->current();

        $notesSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', [])
            ->join(['n' => 'note'], 'n.id = nt.note_id', [])
            ->where(['t.id' => $tag->getId()]);

        $notesResult = $this->sql->prepareStatementForSqlObject($notesSelect)->execute();

        $noteResultSet = new HydratingResultSet(new ReflectionHydrator(), new Note());
        $noteResultSet->initialize($notesResult);

        /** @var Note $note */
        foreach($noteResultSet as $note) {
            $tag->addNote($note);
        }

        return $tag;
    }
}
