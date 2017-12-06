<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class NoteHydrator extends AbstractHydrator
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
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

        if (array_key_exists('category_id', $data)) {
            $category = $this->categoryRepository->findById($data['category_id']);
            $object->setCategory($category);
        };

        if (array_key_exists('tags', $data)) {
            $object->setTags($data['tags']);
        };

        if (array_key_exists('title', $data)) {
            $object->setTitle($data['title']);
        };

        if (array_key_exists('content', $data)) {
            $object->setContent($data['content']);
        };

        if (array_key_exists('date_published', $data)) {
            $object->setDatePublished($data['date_published']);
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
            'category_id' => $object->getCategory()->getId(),
            'tags' => $object->getTags(),
            'title' => $object->getTitle(),
            'content' => $object->getContent(),
            'date_published' => $object->getDatePublished()
        ];
    }
}