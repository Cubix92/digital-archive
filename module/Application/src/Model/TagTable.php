<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class TagTable
{
    protected $tableGateway;

    protected $tagHydrator;

    public function __construct(TableGateway $tableGateway, NoteHydrator $tagHydrator)
    {
         $this->tableGateway = $tableGateway;
         $this->tagHydrator = $tagHydrator;
    }

    public function findAll()
    {
        return $this->tableGateway->select();
    }
}
