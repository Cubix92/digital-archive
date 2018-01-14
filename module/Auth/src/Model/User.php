<?php

namespace Auth\Model;

use Zend\Crypt\Password\Bcrypt;

class User
{
    protected $id;

    protected $email;

    protected $role;

    protected $password;

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

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
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
}
