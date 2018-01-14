<?php

namespace Log\Model;

use Zend\Hydrator\AbstractHydrator;

class LogHydrator extends AbstractHydrator
{
    /**
     * @param Log $object
     * @param array $data
     * @return Log|array
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Log) {
            return $object;
        }

        if (array_key_exists('id', $data)) {
            $object->setId($data['id']);
        };

        if (array_key_exists('content', $data)) {
            $object->setContent($data['content']);
        };

        if (array_key_exists('type', $data)) {
            $object->setType($data['type']);
        };

        if (array_key_exists('date', $data)) {
            $object->setDate(new \DateTime($data['date']));
        };

        return $object;
    }

    /**
     * @param Log $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'id' => $object->getId(),
            'content' => $object->getContent(),
            'type' => $object->getType(),
            'date' => $object->getDate()->format('Y-m-d H:i:s')
        ];
    }
}