<?php

namespace Application\Model;

use Zend\Hydrator\AbstractHydrator;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;
use Zend\Hydrator\Strategy\ClosureStrategy;
use Zend\Hydrator\Reflection as ReflectionHydrator;

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

        if (array_key_exists('category', $data)) {
            $category = $this->categoryRepository->findById($data['category']);
            $object->setCategory($category);
        };

        if (array_key_exists('tags', $data)) {
            $object->setTags($data['tags']);
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

    public function build()
    {
        $categoryStrategy = new ClosureStrategy(
            function($object){
                return $object;
            },
            function($value){
                return (new Category())->setId($value);
            }
        );

        $dateStrategy = new ClosureStrategy(
            function($object){
                return $object->format('Y-m-d H:i:s');
            },
            function($value){
                return new \DateTime($value);
            }
        );

        $namingStrategy = new MapNamingStrategy(array(
            'date_published' => 'datePublished'
        ));

        $reflectionHydrator = (new ReflectionHydrator())
            ->setNamingStrategy($namingStrategy)
            ->addStrategy('category', $categoryStrategy)
            ->addStrategy('datePublished', $dateStrategy);

        return $reflectionHydrator;
    }
}