<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;

class TagAdapter extends AdapterAbstract
{
    public function insert(Tag $tag)
    {
        $insert = new Insert('tag');

        $insert->values([
            'name' => $tag->getName()
        ]);

        $result = $this->executeStatement($insert);
        $id = $result->getGeneratedValue();

        return $id;
    }

    public function delete(Tag $tag)
    {
        if (!$tag->getId()) {
            throw new \RuntimeException('Cannot delete tag; missing identifier.');
        }

        $delete = (new Delete('note'))
            ->where(['id' => $tag->getId()]);

        $this->executeStatement($delete);
    }
}
