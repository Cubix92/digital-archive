<?php

namespace User\Model;

use Zend\Crypt\Password\Bcrypt;

class User
{
    protected $id;

    protected $email;

    protected $password;

    protected $created;

    public function exchangeArray($row)
    {
        $this->id = (!empty($row['id'])) ? $row['id'] : null;
        $this->email = (!empty($row['email'])) ? $row['email'] : null;
        $this->password = (!empty($row['password'])) ? $row['password'] : null;
        $this->created = (!empty($row['created'])) ? new \DateTime($row['created']) : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'email' => $this->email,
            'password'  => $this->password,
            'dateCreated'  => $this->created
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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setHashedPassword($password)
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
