<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;

class TagCommand
{
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql($dbAdapter);
    }

    public function insert(Tag $tag)
    {
        $insert = new Insert('tag');

        $insert->values([
            'name' => $tag->getName()
        ]);

        $result = $this->sql->prepareStatementForSqlObject($insert)->execute();
        $id = $result->getGeneratedValue();

        return $id;
    }

    public function delete(Tag $tag)
    {
        if (!$tag->getId()) {
            throw new \RuntimeException('Cannot delete tag; missing identifier.');
        }

        $deleteNoteTags = (new Delete('note_tag'))
            ->where(['tag_id' => $tag->getId()]);

        $delete = (new Delete('note'))
            ->where(['id' => $tag->getId()]);

        $this->sql->prepareStatementForSqlObject($deleteNoteTags)->execute();
        $this->sql->prepareStatementForSqlObject($delete)->execute();
    }
}
