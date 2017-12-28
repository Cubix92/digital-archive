<?php

namespace Application\Model;

use Application\Service\TagService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class NoteCommand
{
    protected $sql;

    protected $tagService;

    public function __construct(AdapterInterface $dbAdapter, TagService $tagService)
    {
        $this->sql = new Sql($dbAdapter);
        $this->tagService = $tagService;
    }

    public function insert(Note $note)
    {
        $tags = $this->tagService->prepare($note->getTags());
        $note->setTags($tags);

        $insert = new Insert('note');

        $insert->values([
            'category' => $note->getCategory()->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'url' => $note->getUrl(),
            'date_published' => (new \DateTime())->format('Y-m-d H:i:s')
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
        var_dump($note->getTags());die;
        $tags = $this->tagService->prepare($note->getTags());
        $note->setTags($tags);

        $update = new Update('note');

        $update->set([
            'category' => $note->getCategory()->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'url' => $note->getUrl()
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
