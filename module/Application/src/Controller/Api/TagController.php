<?php

namespace Application\Controller\Api;

use Application\Model\Tag;
use Application\Model\TagCommand;
use Application\Model\TagHydrator;
use Application\Model\TagRepository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TagController extends AbstractRestfulController
{
    protected $tagRepository;

    protected $tagCommand;

    protected $tagHydrator;

    public function __construct(TagRepository $tagRepository, TagCommand $tagCommand, TagHydrator $tagHydrator)
    {
        $this->tagRepository = $tagRepository;
        $this->tagCommand = $tagCommand;
        $this->tagHydrator = $tagHydrator;
    }

    public function get($id):JsonModel
    {
        try {
            $tag = $this->tagRepository->findById($id);
        } catch(\UnexpectedValueException $e) {
            return new JsonModel([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        $data = $this->tagHydrator->extract($tag);

        return new JsonModel([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getList():JsonModel
    {
        /** @var Tag $tag */
        $tags = $this->tagRepository->findAll();
        $data = [];

        foreach($tags as $tag) {
            $data[] = $this->tagHydrator->extract($tag);
        }

        return new JsonModel([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function create($data)
    {
        parent::create($data);
    }

    public function update($id, $data)
    {
        parent::update($id, $data);
    }

    public function delete($id)
    {
        parent::delete($id);
    }
}