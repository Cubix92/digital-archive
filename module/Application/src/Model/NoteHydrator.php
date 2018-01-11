<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;

class NoteHydrator extends AbstractHydrator
{
    /**
     * @param Note $object
     * @param array $data
     * @return Note|array
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Note) {
            return $object;
        }

        if (array_key_exists('id', $data)) {
            $object->setId($data['id']);
        };

        if (array_key_exists('category', $data)) {
            $category = (new Category())
                ->setId($data['category']['id'])
                ->setName($data['category']['name'])
                ->setShortcut($data['category']['shortcut']);

            $object->setCategory($category);
        };

        if (array_key_exists('tags', $data)) {
            foreach ($data['tags'] as $tagSet) {
                $tag = (new Tag)
                    ->setId($tagSet['id'])
                    ->setName($tagSet['name']);

                $object->addTag($tag);
            }
        };

        if (array_key_exists('title', $data)) {
            $object->setTitle($data['title']);
        };

        if (array_key_exists('url', $data)) {
            $object->setUrl($data['url']);
        };

        if (array_key_exists('content', $data)) {
            $object->setContent($data['content']);
        };

        if (array_key_exists('date_published', $data)) {
            $object->setDatePublished(new \DateTime($data['date_published']));
        };

        return $object;
    }

    /**
     * @param Note $object
     * @return array
     */
    public function extract($object)
    {
        $tags = [];

        /** @var Tag $tag */
        foreach ((array)$object->getTags() as $tag) {
            $tags[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName()
            ];
        }

        return [
            'id' => $object->getId(),
            'category' => [
                'id' => $object->getCategory()->getId(),
                'name' => $object->getCategory()->getName(),
                'shortcut' => $object->getCategory()->getShortcut()
            ],
            'tags' => $tags,
            'title' => $object->getTitle(),
            'content' => $object->getContent(),
            'url' => $object->getUrl(),
            'date_published' => $object->getDatePublished() ? $object->getDatePublished()->format('Y-m-d H:i:s') : null,
        ];
    }
}