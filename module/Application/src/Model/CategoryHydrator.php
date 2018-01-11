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

        if (array_key_exists('notes', $data)) {
            foreach($data['notes'] as $noteSet) {
                $note = (new Note())
                    ->setId($noteSet['id'])
                    ->setTitle($noteSet['title'])
                    ->setContent($noteSet['content'])
                    ->setUrl($noteSet['url'])
                    ->setDatePublished($noteSet['date_published']);

                $object->addNote($note);
            }
        };

        if (array_key_exists('name', $data)) {
            $object->setTags($data['tags']);
        };

        if (array_key_exists('shortcut', $data)) {
            $object->setShortcut($data['shortcut']);
        };

        return $object;
    }

    /**
     * @param Category $object
     * @return array
     */
    public function extract($object)
    {
        $notes = [];

        /** @var Note $note */
        foreach ((array)$object->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'title' => $note->getTitle(),
                'content' => $note->getContent(),
                'url' => $note->getUrl(),
                'date_published' => $note->getDatePublished(),
            ];
        }

        return [
            'id' => $object->getId(),
            'notes' => $notes,
            'name' => $object->getName(),
            'shortcut' => $object->getShortcut()
        ];
    }
}