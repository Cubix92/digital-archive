<?php

namespace User\Model;

use Zend\Crypt\Password\Bcrypt;

class User
{
    protected $id;

    protected $role;

    protected $games;

    protected $email;

    protected $password;

    protected $dateCreated;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getGames()
    {
        return $this->games;
    }

    public function setGames($games)
    {
        $this->games = $games;
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

    public function getDateCreated():\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }
}
