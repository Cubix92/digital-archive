<?php

namespace Application\Model;

use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

class NoteCommand extends AdapterAbstract
{
    public function insert(Note $note)
    {
        $insert = new Insert('note');

        $insert->values([
            'category_id' => $note->getCategory()->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'position' => $note->getPosition() ? $note->getPosition() : 0
        ]);

        $result = $this->executeStatement($insert);
        $id = $result->getGeneratedValue();

        $note->setId($id);
        $this->linkNoteToTags($note);

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

        $this->executeStatement($update);
        $this->linkNoteToTags($note);
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

        $this->executeStatement($deleteNoteTags);
        $this->executeStatement($delete);
    }

    protected function linkNoteToTags($note)
    {
        $this->sql->getAdapter()->getDriver()->getConnection()->beginTransaction();

        $delete = new Delete('note_tag');
        $delete->where(['note_id' => $note->getId()]);

        try {
            $this->executeStatement($delete);
        } catch(\Exception $e) {
            $this->sql->getAdapter()->getDriver()->getConnection()->rollback();
            throw new \Exception($e);
        }

        $insert = new Insert('note_tag');

        /**
         * @var Tag $tag
         */
        foreach($note->getTags() as $tag) {
            $insert->values([
                'note_id' => $note->getId(),
                'tag_id' => $tag->getId()
            ]);

            try {
                $this->executeStatement($insert);
            } catch(\Exception $e) {
                $this->sql->getAdapter()->getDriver()->getConnection()->rollback();
                throw new \Exception($e);
            }
        }

        $this->sql->getAdapter()->getDriver()->getConnection()->commit();
    }
}
