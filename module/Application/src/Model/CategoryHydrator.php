<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class CategoryHydrator extends AbstractHydrator
{
    protected $noteTable;

    public function __construct(NoteTable $noteTable)
    {
        $this->noteTable = $noteTable;
        parent::__construct();
    }

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

            $notes = $this->noteTable->findByCategoryId($data['id']);
            $object->setNotes($notes);
        };

        if (array_key_exists('name', $data)) {
            $object->setName($data['name']);
        };

        $object->setNotes([]);

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
            'title' => $object->getName(),
            'icon' => $object->getIcon(),
            'position' => $object->getPosition()
        ];
    }
}