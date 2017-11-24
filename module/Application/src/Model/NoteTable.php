<?php

namespace Application\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class NoteTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
         $this->tableGateway = $tableGateway;
    }

    public function findAll()
    {
        return $this->tableGateway->select();
    }

    public function findById($id)
    {
        $result = $this->tableGateway->select(['id' => $id]);
        $note = $result->current();

        if (!$note) {
            throw new \InvalidArgumentException(sprintf(
                'Note with identifier "%s" not found',
                $id
            ));
        }

        return $note;
    }

    public function save(Note $note)
    {
        $data = [
            'title' => $note->getTitle(),
            'category_id' => $note->getCategory() ? $note->getCategory()->getId() : null,
            'content' => $note->getContent(),
            'position' => 0
        ];

        $id = $note->getId();

        if ($id == 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->findById($id)) {
            throw new \RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
}
