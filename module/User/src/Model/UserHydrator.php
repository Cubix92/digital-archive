<?php

namespace User\Model;

use Zend\Hydrator\AbstractHydrator;

class UserHydrator extends AbstractHydrator
{
    protected $gameTable;

    public function __construct(GameTable $gameTable)
    {
        $this->gameTable = $gameTable;
    }

    public function hydrate(array $data, $object)
    {
        if (!$object instanceof User) {
            return $object;
        }

        if (array_key_exists('id', $data)) {
            $object->setId($data['id']);
        };

        $object->setGames($this->gameTable->findByUserId($data['id']));

        if (array_key_exists('email', $data)) {
            $object->setEmail($data['email']);
        };

        if (array_key_exists('password', $data)) {
            $object->setPassword($data['password']);
        };

        if (array_key_exists('date_created', $data)) {
            $object->setDateCreated(new \DateTime($data['date_created']));
        };

        return $object;
    }

    public function extract($object)
    {
        return [
            'id' => $object->getId(),
            'email' => $object->getEmail(),
            'password' => $object->getPassword(),
            'date_created' => $object->getDateCreated()
        ];
    }
}