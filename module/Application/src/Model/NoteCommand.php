<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class NoteCommand
{
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql($dbAdapter);
    }

    public function insert(Note $note)
    {
        $insert = new Insert('note');

        $insert->values([
            'category_id' => $note->getCategory()->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'position' => $note->getPosition() ? $note->getPosition() : 0
        ]);

        $result = $this->sql->prepareStatementForSqlObject($insert)->execute();
        $id = $result->getGeneratedValue();

        $note->setId($id);
        $this->linkTags($note);

        return $id;
    }

    public function update(Note $note)
    {
        if (!$note->getId()) {
            throw new \RuntimeException('Cannot update post; missing identifier.');
        }

        $update = new Update('note');

        $update->set([
            'category_id' => $note->getCategory()->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'position' => $note->getPosition()
        ]);
        $update->where(['id = ?' => $note->getId()]);

        $this->sql->prepareStatementForSqlObject($update)->execute();
        $this->linkTags($note);
    }

    public function delete(Note $note)
    {
        if (!$note->getId()) {
            throw new \RuntimeException('Cannot delete note; missing identifier.');
        }

        $deleteNoteTags = (new Delete('note_tag'))
            ->where(['note_id' => $note->getId()]);

        $delete = (new Delete('note'))
            ->where(['id' => $note->getId()]);

        $this->sql->prepareStatementForSqlObject($deleteNoteTags)->execute();
        $this->sql->prepareStatementForSqlObject($delete)->execute();
    }

    protected function linkTags(Note $note)
    {
        $this->sql->getAdapter()->getDriver()->getConnection()->beginTransaction();

        $delete = new Delete('note_tag');
        $delete->where(['note_id' => $note->getId()]);
        $this->sql->prepareStatementForSqlObject($delete)->execute();

        $insert = new Insert('note_tag');

        /**
         * @var Tag $tag
         */
        foreach($note->getTags() as $tag) {
            $insert->values([
                'note_id' => $note->getId(),
                'tag_id' => $tag->getId()
            ]);

            $this->sql->prepareStatementForSqlObject($insert)->execute();
        }

        $this->sql->getAdapter()->getDriver()->getConnection()->commit();
    }
}
