<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class NoteHydrator extends AbstractHydrator
{
    protected $categoryTable;

    public function __construct(CategoryTable $categoryTable)
    {
        $this->categoryTable = $categoryTable;
    }

    /**
     * @param Note $object
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

        if (array_key_exists('category_id', $data)) {
            $category = $this->categoryTable->findById($data['category_id']);
            $object->setCategory($category);
        };

        if (array_key_exists('title', $data)) {
            $object->setTitle($data['title']);
        };

        if (array_key_exists('content', $data)) {
            $object->setContent($data['content']);
        };

        if (array_key_exists('position', $data)) {
            $object->setPosition($data['position']);
        };

        return $object;
    }

    /**
     * @param Note $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'id' => $object->getId(),
            'category' => $object->getCategory(),
            'title' => $object->getTitle(),
            'content' => $object->getContent(),
            'position' => $object->getPosition()
        ];
    }
}