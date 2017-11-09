<?php

namespace User\Model;

use Zend\Crypt\Password\Bcrypt;

class User
{
    protected $id;

    protected $username;

    protected $password;

    protected $created;

    public function exchangeArray($row)
    {
        $this->id = (!empty($row['id'])) ? $row['id'] : null;
        $this->username = (!empty($row['username'])) ? $row['username'] : null;
        $this->password = (!empty($row['password'])) ? $row['password'] : null;
        $this->dateCreated = (!empty($row['date_created'])) ? new \DateTime($row['date_created']) : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'username' => $this->username,
            'password'  => $this->password,
            'dateCreated'  => $this->dateCreated
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $bcrypt = new Bcrypt();

        $this->password = $bcrypt->create($password);
        return $this;
    }

    public function getCreated():\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }
}
