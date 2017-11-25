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
        $tags = [];

        /**
         * @var Tag $tag
         */
        foreach($tags as $tag) {
            if (!$tag->getId()) {
                try {
                    $id = $this->tagCommand->insert($tag);
                    $tag->setId($id);
                } catch(\RuntimeException $e) {
                    throw new \RuntimeException($e->getMessage());
                }
            }

            if ($this->tagRepository->findByName($tag->getName())) {

            }

            $tags[] = $tag;
        }
    }
}