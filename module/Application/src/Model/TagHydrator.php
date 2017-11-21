<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class TagHydrator extends AbstractHydrator
{
    /**
     * @param Tag $object
     * @param array $data
     * @return object|array
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Note) {
            return $object;
        }

        if (array_key_exists('id', $data)) {
            $object->setId($data['id']);
        };

        if (array_key_exists('name', $data)) {
            $object->setName($data['name']);
        };

        return $object;
    }

    /**
     * @param Tag $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName()
        ];
    }
}