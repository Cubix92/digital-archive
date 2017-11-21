<?php

namespace Application\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class NoteTable
{
    protected $tableGateway;

    protected $noteHydrator;

    public function __construct(TableGateway $tableGateway, NoteHydrator $noteHydrator)
    {
         $this->tableGateway = $tableGateway;
         $this->noteHydrator = $noteHydrator;
    }

    public function findAll()
    {
        return $this->tableGateway->select();
    }

    public function findById($id)
    {
        $result = $this->tableGateway->select(['id' => $id]);

        if (!$result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new \RuntimeException(sprintf(
                'Failed retrieving note with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->noteHydrator, new Note());
        $resultSet->initialize($result);
        $note = $resultSet->current();

        if (!$note) {
            throw new \InvalidArgumentException(sprintf(
                'Note with identifier "%s" not found.',
                $id
            ));
        }

        return $note;
    }

    public function findByCategoryId($id)
    {
        $result = $this->tableGateway->select(['category_id' => $id]);

        if (!$result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new \RuntimeException(sprintf(
                'Failed retrieving note with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->noteHydrator, new Note());
        $resultSet->initialize($result);
        $note = $resultSet->current();

        if (!$note) {
            throw new \InvalidArgumentException(sprintf(
                'Note with identifier "%s" not found.',
                $id
            ));
        }

        return $note;
    }
}
