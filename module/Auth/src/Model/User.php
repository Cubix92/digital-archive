<?php

namespace Auth\Model;

use Zend\Crypt\Password\Bcrypt;

/**
 * @Annotation\Name("user")
 * @Annotation\Hydrator("UserHydrator")
 */
class User
{
    protected $id;

    protected $email;

    protected $password;

    protected $dateCreated;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
