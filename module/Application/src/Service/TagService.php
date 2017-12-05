<?php

namespace Application\Service;

use Application\Model\Tag;
use Application\Model\TagCommand;
use Application\Model\TagRepository;

class TagService
{
    protected $tagRepository;

    protected $tagCommand;

    public function __construct(TagRepository $tagRepository, TagCommand $tagCommand)
    {
        $this->tagRepository = $tagRepository;
        $this->tagCommand = $tagCommand;
    }

    public function prepare(array $tags): array
    {
        $filteredTags = [];

        /**
         * @var Tag $tag
         */
        foreach($tags as $tag) {
            if (!$tag->getId()) {
                try {
                    $tag = $this->tagRepository->findByName($tag->getName());
                } catch(\UnexpectedValueException $e) {
                    $id = $this->tagCommand->insert($tag);
                    $tag->setId($id);
                }
            }

            $filteredTags[] = $tag;
        }

        return $filteredTags;
    }
}