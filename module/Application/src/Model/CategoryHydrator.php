<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class CategoryHydrator extends AbstractHydrator
{
    /**
     * @param Category $object
     * @param array $data
     * @return object|array
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Category) {
            return $object;
        }

        if (array_key_exists('id', $data)) {
            $object->setId($data['id']);
        };

        if (array_key_exists('name', $data)) {
            $object->setName($data['name']);
        };

        if (array_key_exists('notes', $data)) {
            $object->setNotes($data['notes']);
        };

        if (array_key_exists('icon', $data)) {
            $object->setIcon($data['icon']);
        };

        if (array_key_exists('position', $data)) {
            $object->setPosition($data['position']);
        };

        return $object;
    }

    /**
     * @param Category $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'id' => $object->getId(),
            'notes' => $object->getNotes(),
            'name' => $object->getName(),
            'icon' => $object->getIcon(),
            'position' => $object->getPosition()
        ];
    }
}