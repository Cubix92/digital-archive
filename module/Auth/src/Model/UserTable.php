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

    public function findAll()
    {
        return $this->tableGateway->select();
    }

    public function findById($id)
    {
        $resultSet = $this->tableGateway->select(['id' => $id]);
        $user = $resultSet->current();

        if (!$user) {
            throw new \InvalidArgumentException(sprintf('User with identifier "%s" not found.', $id));
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

        if (!$this->findById($id)) {
            throw new \RuntimeException(sprintf('Cannot update user with identifier %d; does not exist', $id));
        }

        if (!$data['password']) {
            unset($data['password']);
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function delete($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
