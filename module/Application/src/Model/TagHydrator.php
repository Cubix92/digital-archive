<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class TagHydrator extends AbstractHydrator
{
    protected $categoryRepository;

    public function __construct(NoteRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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

        if (array_key_exists('notes', $data)) {

        };

        if (array_key_exists('name', $data)) {
            $object->setTags($data['tags']);
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
            'tags' => $object->getTags(),
            'title' => $object->getTitle(),
            'url' => $object->getUrl(),
            'content' => $object->getContent(),
            'date_published' => $object->getDatePublished() ? $object->getDatePublished()->format('Y-m-d H:i:s') : null,
        ];
    }
}