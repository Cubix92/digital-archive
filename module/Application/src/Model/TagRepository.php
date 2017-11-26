<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Hydrator\Reflection;

class TagRepository extends AdapterAbstract
{
    public function findAll()
    {
        $tags = [];
        $select = new Select('tag');
        $result = $this->executeStatement($select);

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
        $select = (new Select('tag'))->where(['id' => $id]);
        $result = $this->executeStatement($select);

        if (!$result->valid()) {
            throw new \InvalidArgumentException(sprintf('Tag with identifier "%s" not found', $id));
        }

        $resultSet = new HydratingResultSet(new ReflectionHydrator(), new Tag());
        $resultSet->initialize($result);

        /** @var Tag $tag */
        $tag = $resultSet->current();

        $notesSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id')
            ->join(['n' => 'note'], 'n.id = nt.note_id')
            ->where(['t.id' => $id]);

        $notesResult = $this->executeStatement($notesSelect);

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
        $select = (new Select('tag'))->where(['name' => $name]);
        $result = $this->executeStatement($select);

        if (!$result->valid()) {
            throw new \InvalidArgumentException(sprintf('Tag with name "%s" not found', $name));
        }

        $resultSet = new HydratingResultSet(new ReflectionHydrator(), new Tag());
        $resultSet->initialize($result);

        /** @var Tag $tag */
        $tag = $resultSet->current();

        $notesSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', [])
            ->join(['n' => 'note'], 'n.id = nt.note_id', [])
            ->where(['t.id' => $tag->getId()]);

        $notesResult = $this->executeStatement($notesSelect);

        $noteResultSet = new HydratingResultSet(new ReflectionHydrator(), new Note());
        $noteResultSet->initialize($notesResult);

        /** @var Note $note */
        foreach($noteResultSet as $note) {
            $tag->addNote($note);
        }

        return $tag;
    }
}
