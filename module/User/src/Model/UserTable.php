<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    public function __construct(TableGateway $tableGateway)
    {
         $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function findUserById($id)
    {
//        $sql       = new Sql($this->db);
//        $select    = $sql->select('posts');
//        $select->where(['id = ?' => $id]);
//
//        $statement = $sql->prepareStatementForSqlObject($select);
//        $result    = $statement->execute();
//
//        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
//            throw new RuntimeException(sprintf(
//                'Failed retrieving blog post with identifier "%s"; unknown database error.',
//                $id
//            ));
//        }
//
//        $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
//        $resultSet->initialize($result);
//        $post = $resultSet->current();
//
//        if (! $post) {
//            throw new InvalidArgumentException(sprintf(
//                'Blog post with identifier "%s" not found.',
//                $id
//            ));
//        }
//
//        return $post;
    }

    public function getUser($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function save(User $user)
    {
        $data = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'date_created' => date('Y-m-d H:i:s'),
        ];

        $id = $user->getId();

        if ($id == 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->getUser($id)) {
            throw new \RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function delete($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
