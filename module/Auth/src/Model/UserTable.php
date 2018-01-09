<?php

namespace Auth\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getUser($id)
    {
        $resultSet = $this->tableGateway->select(['id' => $id]);
        $user = $resultSet->current();

        if (!$user) {
            throw new \InvalidArgumentException('User with identifier not found');
        }

        return $user;
    }

    public function save(User $user)
    {
        $data = [
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'password' => $user->getPassword(),
        ];

        $id = $user->getId();

        if ($id == 0) {
            $data['date_created'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->getUser($id)) {
            throw new \RuntimeException(sprintf('Cannot update user that does not exist'));
        }

        if (!$data['password']) {
            unset($data['password']);
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function delete($id)
    {
        $this->tableGateway->delete(['id' => (int)$id]);
    }
}
